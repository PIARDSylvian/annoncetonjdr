<?php

namespace App\Repository;

use App\Entity\Party;
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
    * @return Party[] Returns an array of Party objects
    */
    public function search($value)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.gameName = :gameName_id')
            ->addSelect('( 6371 * acos( cos( radians(:lat) ) * cos( radians( p.lat ) ) * cos( radians( p.lng ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( p.lat ) ) ) ) AS distance')
            ->having('distance <= :radius')
            ->setParameter('lat', $value['lat'])
            ->setParameter('lng', $value['lng'])
            ->setParameter('radius', $value['radius'])
            ->setParameter('gameName_id', '2')
            ->orderBy('distance', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
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
