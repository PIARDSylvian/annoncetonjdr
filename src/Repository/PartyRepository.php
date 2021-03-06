<?php

namespace App\Repository;

use App\Entity\Party;
// use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;

/**
 * @method Party|null find($id, $lockMode = null, $lockVersion = null)
 * @method Party|null findOneBy(array $criteria, array $orderBy = null)
 * @method Party[]    findAll()
 * @method Party[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Party::class);
    }

    // /**
    //  * @return Party[] Returns an array of Party objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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

    public function findByOwnerQueryBuilder($owner)
    {
        return $this->createQueryBuilder("p")
            ->where(":user = p.owner")
            ->setParameter('user', $owner)
            ->orderBy('p.date', 'DESC')
        ;
    }

    public function findByRegisteredPlayerQueryBuilder($registeredPlayer)
    {
        return $this->createQueryBuilder("p")
            ->where(":user MEMBER OF p.registeredPlayers")
            ->setParameter('user', $registeredPlayer)
            ->orderBy('p.date', 'DESC')
        ;
    }
}
