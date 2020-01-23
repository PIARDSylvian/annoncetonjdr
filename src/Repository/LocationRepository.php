<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
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

        $query = $this->createQueryBuilder('l')
            ->addSelect('( 6371 * acos( cos( radians(:lat) ) * cos( radians( l.lat ) ) * cos( radians( l.lng ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( l.lat ) ) ) ) AS distance')
                ->setParameter('lat', 48.866667)
                ->setParameter('lng', 2.333333)
            ->orderBy('distance', 'ASC')
            ->leftJoin('l.parties', 'p WITH p.date >= ?1')
                ->addSelect('p')
                ->setParameter('1', new \DateTime('now'))
            ->leftJoin('l.events', 'e WITH e.dateFinish >= ?1')
                ->addSelect('e')
                ->setParameter('1', new \DateTime('now'))
            ->leftJoin('l.association', 'a')
                ->addSelect('a')
            ;
            
            return $query->getQuery()->getResult();
        }

    // /**
    //  * @return Location[] Returns an array of Location objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Location
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
