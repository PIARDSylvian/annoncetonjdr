<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //user admin
        $user = new User();
        $user->setEmail('piard.sylvian+admin@gmail.com');
        
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin123'
        ));
        $user->setPseudonym('admin');
        $user->setSecretQ('sq1');
        $user->setSecretR('sq1');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        //user
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail('piard.sylvian+'.$i.'@gmail.com');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $i.$i.$i.$i.$i.$i.$i.$i
            ));
            $user->setPseudonym('user'.$i);
            $user->setSecretQ('sq1');
            $user->setSecretR('sq1');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
