<?php

namespace App\Repository;

use App\Entity\Party;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Party|null find($id, $lockMode = null, $lockVersion = null)
 * @method Party|null findOneBy(array $criteria, array $orderBy = null)
 * @method Party[]    findAll()
 * @method Party[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Party::class);
    }

    /**
    * @return Party[] Query
    */
    public function searchQuery(Search $search)
    {
        if (!$search->getSearchLat() OR !$search->getSearchLng()) {
            // Latitude & longitude de Paris
            $search->setSearchLat('48.866667')->setSearchLng('2.333333');
        }

        if (!$search->getDistance()) {$search->setDistance('2000');}

        $query =  $this->createQueryBuilder('p')
            ->select('p');
                if ($search->getOnline()) {
                    $query->leftJoin('p.address', 'address');
                }
                else {
                    $query->andWhere('p.online = :online')->setParameter('online', $search->getOnline());
                    $query->innerJoin('p.address', 'address');
                }
                $query->addSelect('address')
                ->addSelect('( 6371 * acos( cos( radians(:lat) ) * cos( radians( address.lat ) ) * cos( radians( address.lng ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( address.lat ) ) ) ) AS distance')
                ->having('distance IS NULL or distance <= :radius')
                    ->setParameter('lat', $search->getSearchLat())
                    ->setParameter('lng', $search->getSearchLng())
                    ->setParameter('radius', $search->getDistance())
                ->orderBy('distance', 'ASC');

                if ($search->getPartyName()) {
                    $query->andWhere('p.partyName LIKE :partyName')->setParameter('partyName', '%'.$search->getPartyName().'%');
                }

                if ($search->getGameName()) {
                    $query->andWhere('p.gameName = :gameName')->setParameter('gameName', $search->getGameName());
                }

                if ($search->getPeriod()) {
                    $query->andWhere('p.date BETWEEN :from AND :to')
                        ->setParameter('from', new \DateTime('now'))
                        ->setParameter('to', new \DateTime($search->getPeriod()))
                    ;
                }
                else {
                    $query->andWhere('p.date >= :now')
                        ->setParameter('now', new \DateTime('now'))
                    ;
                }
        ;

        return $query->getQuery();
    }

    /*
    public function findOneBySomeField($value): ?Party
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
