<?php

namespace App\Repository;
 
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;
 
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($pseudonym)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email OR u.pseudonym = :pseudonym')
            ->setParameter('email', $pseudonym)
            ->setParameter('pseudonym', $pseudonym)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
