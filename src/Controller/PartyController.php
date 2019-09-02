<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\PartyType;
use App\Entity\Party;

class PartyController extends AbstractController {

    /**
     * @Route("/party", name="app_party")
     */
    public function party(Request $request)
    {
        $party = new Party();
        $form = $this->createForm(PartyType::class, $party);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $this->getDoctrine()->getRepository(Party::class);
            $search = $repository->search(['lat'=> '48.866667','lng'=> '2.333333','radius'=> '73']);
    
            $result=[];
        
            foreach( $search as $value ) {
                $value[0]->distance = $value['distance'];
                $result[] = $value[0];
            }

            return $this->render('party/party.html.twig',array('form' => $form->createView(), 'allParty' => $result));
        }


        return $this->render('party/party.html.twig',array('form' => $form->createView(), 'allParty' => null));
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

            return $this->redirectToRoute('home');
        }

        return $this->render('party/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/party/{id}", name="app_party_show", requirements={"id"="\d+"})
     */
    public function show($id)
    {
        $repository = $this->getDoctrine()->getRepository(Party::class);
        $party = $repository->find($id);

        return $this->render('party/show.html.twig', ['party' => $party]);
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
}
