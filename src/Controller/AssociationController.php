<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Association;
use App\Form\AssociationType;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\ByteString;

class AssociationController extends AbstractController {
    
    /**
     * @Route("/association/create", name="app_association_create")
     * @Route("/association/update/{slug}", name="app_association_update")
     */
    public function create(Association $association = null, Request $request, SluggerInterface $slugger)
    {
        if(!$association) {
            $association = new Association();
        }

        $form = $this->createForm(AssociationType::class, $association);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$association->getSlug()) {
                $association->setSlug($slugger->slug(ByteString::fromRandom(6).'-'.$association->getName())->lower());
            }
            $association->setPendding(true);
            $association->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('app_association_show', ['slug' => $association->getSlug()]);
        }

        return $this->render('association/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/association/{slug}", name="app_association_show")
     */
    public function show(Association $association, Request $request, PaginatorInterface $paginator)
    {
        if ($association->getPendding()) {
            $this->addFlash('danger', 'Association en attente de validation');

            return $this->render('association/show.html.twig', ['association' => $association]);
        }
        else {

            $repository = $this->getDoctrine()->getRepository(Commentary::class);

            $partyQB = $repository->findByAssocQueryBuilder($association);
            $commentaries = $paginator->paginate(
                $partyQB,
                $request->query->getInt('page', 1),
                10
            );

            $commentaries->setCustomParameters(['align' => 'center']);

            $commentary = new Commentary();
            $form = $this->createForm(CommentaryType::class, $commentary);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $commentary->setOwner($this->getUser());
                $commentary->setAssociation($association);
                $commentary->setCreatedAt(new \DateTime('now'));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($commentary);
                $entityManager->flush();

                return $this->redirectToRoute('app_association_show', ['slug' => $association->getSlug()]);
            }
        }

        return $this->render('association/show.html.twig', ['association' => $association, 'form' => $form->createView(), 'commentaries' => $commentaries]);
    }

    /**
     * @Route("/association/delete/{id}", name="app_association_delete", requirements={"id"="\d+"})
     */
    public function delete(Association $association)
    {
        if ($this->getUser() === $association->getOwner()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($association);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/association/delete/{id}/com/{comment_id}", name="app_association_delete_com", requirements={"id"="\d+", "cid"="\d+"})
     * @Entity("commentary", expr="repository.find(comment_id)")
     */
    public function deleteCom(Association $association, Commentary $commentary)
    {
        if (!$association->getPendding() && ($this->getUser() === $association->getOwner() || $this->getUser() === $commentary->getOwner())) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_association_show', ['slug' => $association->getSlug()]);
    }

    /**
     * @Route("/association/delete-all-com/{id}", name="app_association_delete_all_com", requirements={"id"="\d+"})
     */
    public function deleteAllCom(Association $association)
    {
        if (!$association->getPendding() && ($this->getUser() === $association->getOwner())) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($association->getCommentaries() as $commentary) {
                $entityManager->remove($commentary);
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_association_show', ['slug' => $association->getSlug()]);
    }
}
