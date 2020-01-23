<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Association;
use App\Form\AssociationType;

use App\Entity\Commentary;
use App\Entity\Event;
use App\Repository\LocationRepository;
use App\Entity\Search;
use App\Form\SearchType;
use App\Form\CommentaryType;

class AssociationController extends AbstractController {
    
    /**
     * @Route("/association/create", name="app_association_create")
     * @Route("/association/update/{id}", name="app_association_update", requirements={"id"="\d+"})
     */
    public function create(Association $association = null, Request $request)
    {
        if(!$association) {
            $association = new Association();
        }

        $form = $this->createForm(AssociationType::class, $association);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $association->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('app_association_show', ['id' => $association->getId()]);
        }

        return $this->render('association/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/association/{id}", name="app_association_show", requirements={"id"="\d+"})
     */
    public function show(Association $association, Request $request)
    {
        // $commentary = new Commentary();
        // $form = $this->createForm(CommentaryType::class, $commentary);

        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {

        //     $commentary->setOwner($this->getUser());
        //     $commentary->setParty($party);
        //     $commentary->setCreatedAt(new \DateTime('now'));
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($commentary);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        // }

        // return $this->render('association/show.html.twig', ['association' => $association, 'form' => $form->createView()]);
        return $this->render('association/show.html.twig', ['association' => $association]);
    }
}
