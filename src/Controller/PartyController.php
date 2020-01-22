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

class PartyController extends AbstractController {

    /**
     * @Route("/party", name="app_party")
     */
    public function party(LocationRepository $repository, Request $request)
    {
        
        // $party = new Party();
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('search')->getValue();

        $form->handleRequest($request);
        if(!$form->isSubmitted()) {
            $form->submit(['page'=> 0, '_token' => $token]);
        }

        $searchResult = $repository->searchQuery($search);

        $result = [];

        foreach( $searchResult as $value ) {

            if (count($value[0]->getEvents()) || count($value[0]->getParties()) ) {
                $value[0]->distance = $value['distance'];
                $result[] = $value[0];
            }
        }

        return $this->render('party/party.html.twig',array('form' => $form->createView(), 'allParty' => $result));
    }
    
    /**
     * @Route("/party/create", name="app_party_create")
     */
    public function create(Request $request)
    {
        $party = new Party();
        $form = $this->createForm(PartyType::class, $party);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $party->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($party);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('party/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/party/{id}", name="app_party_show", requirements={"id"="\d+"})
     */
    public function show($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Party::class);
        $party = $repository->find($id);

        $commentary = new Commentary();

        $form = $this->createForm(CommentaryType::class, $commentary);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $commentary->setOwner($this->getUser());
            $commentary->setParty($party);
            $commentary->setCreatedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentary);
            $entityManager->flush();

            return $this->redirectToRoute('app_party_show', ['id' => $id]);
        }

        return $this->render('party/show.html.twig', ['party' => $party, 'form' => $form->createView()]);
    }

    /**
     * @Route("/party/addPlayer/{id}", name="app_party_add", requirements={"id"="\d+"})
     */
    public function addPlayer($id)
    {
        $repository = $this->getDoctrine()->getRepository(Party::class);
        $party = $repository->find($id);
        
        $party->addRegisteredPlayer($this->getUser());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_party_show', ['id' => $id]);
    }

    /**
     * @Route("/party/removePlayer/{id}", name="app_party_remove", requirements={"id"="\d+"})
     */
    public function removePlayer($id)
    {
        $repository = $this->getDoctrine()->getRepository(Party::class);
        $party = $repository->find($id);

        $party->removeRegisteredPlayer($this->getUser());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_party_show', ['id' => $id]);
    }

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
