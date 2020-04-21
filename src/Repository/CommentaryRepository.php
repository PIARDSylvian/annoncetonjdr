<?php

namespace App\Repository;

use App\Entity\Commentary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commentary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentary[]    findAll()
 * @method Commentary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentary::class);
    }

    // /**
    //  * @return Commentary[] Returns an array of Commentary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentary
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByPartyQueryBuilder($party)
    {
        return $this->createQueryBuilder("e")
            ->where(":party = e.party")
            ->setParameter('party', $party)
            ->orderBy('e.createdAt', 'DESC')
        ;
    }

    public function findByEventQueryBuilder($event)
    {
        return $this->createQueryBuilder("e")
            ->where(":event = e.event")
            ->setParameter('event', $event)
            ->orderBy('e.createdAt', 'DESC')
        ;
    }

    public function findByAssocQueryBuilder($assoc)
    {
        return $this->createQueryBuilder("e")
            ->where(":assoc = e.association")
            ->setParameter('assoc', $assoc)
            ->orderBy('e.createdAt', 'DESC')
        ;
    }
}
