<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Party;
use App\Entity\Commentary;
use App\Entity\Event;
use App\Repository\LocationRepository;
use App\Form\PartyType;
use App\Entity\Search;
use App\Form\SearchType;
use App\Form\CommentaryType;

class EventController extends AbstractController {

    /**
     * @Route("/event/create", name="app_event_create")
     */
    public function createEvent(Request $request)
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $event->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('party/create.html.twig',array('form' => $form->createView()));
    }
}
