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

    // SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;

    /**
    * @return Party[] Query
    */
    public function searchQuery(Search $search)
    {
        $query =  $this->createQueryBuilder('p')
            ->select('p')
            ->addSelect('( 6371 * acos( cos( radians(:lat) ) * cos( radians( p.lat ) ) * cos( radians( p.lng ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( p.lat ) ) ) ) AS distance')
            ->having('distance <= :radius')
            ->setParameter('lat', $search->getLat())
            ->setParameter('lng', $search->getLng())
            ->setParameter('radius', $search->getDistance())
            ->orderBy('distance', 'ASC')
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
