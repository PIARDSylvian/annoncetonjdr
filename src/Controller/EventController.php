<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\ByteString;

class EventController extends AbstractController {

    /**
     * @Route("/event/create", name="app_event_create")
     * @Route("/event/update/{slug}", name="app_event_update")
     */
    public function create(Event $event = null, Request $request, SluggerInterface $slugger)
    {
        if($event && $event->getDateFinish() <= new \DateTime('now')) {
            $this->addFlash('danger', 'Evénement terminé');
            return $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
        }
        if($event && $event->getDateStart() <= new \DateTime('+2 hours')) {
            $this->addFlash('danger', 'Modification non autorisé, 2h avant le début');
            return $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
        } elseif (!$event) {
            $event = new Event();
        }

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$event->getSlug()) {
                $event->setSlug($slugger->slug(ByteString::fromRandom(6).'-'.$event->getName().'-'.$event->getDateStart()->format('d-m-Y'))->lower());
            }
            $event->setPendding(true);
            $event->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
        }

        return $this->render('event/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/event/{slug}", name="app_event_show")
     */
    public function show(Event $event, Request $request, PaginatorInterface $paginator)
    {
        if ($event->getPendding()) {
            $this->addFlash('danger', 'Evenement en attente de validation');

            return $this->render('event/show.html.twig', ['event' => $event]);
        }
        else {
            $repository = $this->getDoctrine()->getRepository(Commentary::class);

            $partyQB = $repository->findByEventQueryBuilder($event);
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
                $commentary->setEvent($event);
                $commentary->setCreatedAt(new \DateTime('now'));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($commentary);
                $entityManager->flush();
                
                return $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
            }
        }
        
        return $this->render('event/show.html.twig', ['event' => $event, 'form' => $form->createView(),'commentaries' => $commentaries]);
    }

    /**
     * @Route("/event/delete/{id}", name="app_event_delete", requirements={"id"="\d+"})
     */
    public function delete(Event $event)
    {
        if ($this->getUser() === $event->getOwner()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/event/delete/{id}/com/{comment_id}", name="app_event_delete_com", requirements={"id"="\d+", "cid"="\d+"})
     * @Entity("commentary", expr="repository.find(comment_id)")
     */
    public function deleteCom(Event $event, Commentary $commentary)
    {
        if (!$event->getPendding() && ($this->getUser() === $event->getOwner() || $this->getUser() === $commentary->getOwner())) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
    }

    /**
     * @Route("/event/delete-all-com/{id}", name="app_event_delete_all_com", requirements={"id"="\d+"})
     */
    public function deleteAllCom(Event $event)
    {
        if (!$event->getPendding() && ($this->getUser() === $event->getOwner())) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($event->getCommentaries() as $commentary) {
                $entityManager->remove($commentary);
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
    }
}
