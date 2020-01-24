<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocationRepository;
use App\Entity\Search;
use App\Form\SearchType;

class SearchController extends AbstractController {

    /**
     * @Route("/search", name="app_search")
     */
    public function search(LocationRepository $repository, Request $request)
    {
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
            if (count($value[0]->getEvents()) || count($value[0]->getParties()) || $value[0]->getAssociation()) {
                $value[0]->distance = $value['distance'];
                $result[] = $value[0];
            }
        }
        return $this->render('search/search.html.twig',array('form' => $form->createView(), 'allParty' => $result));
    }
}
