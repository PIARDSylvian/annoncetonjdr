<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PartyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use App\Form\PartyType;
use App\Form\SearchType;
use App\Entity\Party;
use App\Entity\Search;

class PartyController extends AbstractController {

    /**
     * @Route("/party", name="app_party")
     */
    public function party(PaginatorInterface $paginator, PartyRepository $repository, Request $request)
    {
        
        $party = new Party();
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        
        $form->handleRequest($request);
            $searchResult = $paginator->paginate($repository->searchQuery($search), $request->query->getInt('page', 1), 2, array('wrap-queries'=>true));

            $result = [];
            foreach( $searchResult as $value ) {
                $value[0]->distance = $value['distance'];
                $result[] = $value[0];
            }

            $searchResult->setItems($result);


        return $this->render('party/party.html.twig',array('form' => $form->createView(), 'allParty' => $searchResult));
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
