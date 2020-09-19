<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocationRepository;
use App\Repository\GameRepository;
use App\Entity\Search;
use App\Entity\Location;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController {

    /**
     * @Route("/search", name="app_search")
     */
    public function search(LocationRepository $locationRepository, GameRepository $gameRepository, Request $request, SerializerInterface $serializer)
    {
        if($request->isXMLHttpRequest()) {
            try{
                $data = json_decode($request->getContent(), true);
                
                $step = $data['step'];
                $lat = $data['lat'];
                $lng = $data['lng'];
                $address = $data['address'];
                // throw new \Exception('Something went wrong!');
            }
            catch(\Exception $e){
                error_log($e->getMessage());
                return new JsonResponse([
                    'error' => $e->getMessage()
                ], 500);
            }

            if($lat == null && $lng == null) {
                return new JsonResponse([]);
            }

            $searchResult = $locationRepository->searchQuery((new Search())->setPage($step)->setSearchLat($lat)->setSearchLng($lng));

            $location = (new Location())->setLat($lat)->setLng($lng)->setAddress($address)->setDistance(0);

            $output = [];
            if ( count($searchResult) > 0 ) {
                foreach( $searchResult as $idx => $result ) {
                    if($step == 0 && $idx == 0 && $result[0]->getLat() != $location->getLat() && $result[0]->getLng() != $location->getLng() && $result[0]->getAddress() != $location->getAddress()) {
                        $output[] = $location;
                    }
                    if ($result['distance'] == null) {
                        $result['distance'] = 0;
                    }
                    $result[0]->setDistance($result['distance']);
                    $output[] = $result[0];
                }
            }

            $json = $serializer->serialize($output,'json',['groups'=>'card']);
            
            return JsonResponse::fromJsonString($json);
        }

        $allGames = $gameRepository->findAll();

        return $this->render('search/search.html.twig', array('allGames' => $allGames));
    }
}
