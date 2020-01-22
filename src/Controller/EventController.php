<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Form\EventType;

use App\Entity\Commentary;
use App\Form\CommentaryType;

class EventController extends AbstractController {

    /**
     * @Route("/event/create", name="app_event_create")
     * @Route("/event/update/{id}", name="app_event_update", requirements={"id"="\d+"})
     */
    public function create(Event $event = null, Request $request)
    {
        if(!$event) {
            $event = new Event();
        }

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $event->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/event/delete/{id}", name="app_event_delete", requirements={"id"="\d+"})
     */
    public function delete(Event $event)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/event/{id}", name="app_event_show", requirements={"id"="\d+"})
     */
    public function show(Event $event,Request $request)
    {
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

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/show.html.twig', ['event' => $event, 'form' => $form->createView()]);
    }
}
