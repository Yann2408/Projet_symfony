<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {      
            $user1 = new User ();
            $user1->setEmail('admin@wildseries.com');
            $user1->setRoles(['admin']);
            $user1->setPassword($this->passwordHasher->hashPassword($user1, 'admin'));

            $manager->persist($user1);

            $user2 = new User();        
            $user2->setEmail('contri@wildseries.com');
            $user2->setRoles(['contributeur']);
            $user2->setPassword($this->passwordHasher->hashPassword($user2, 'contri'));

            $manager->persist($user2);

            $manager->flush();
    }
}
