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
            $user1->setRoles(['ROLE_ADMIN']);
            $user1->setPrenom('Monsieur');
            $user1->setNom('admin');
            $user1->setPassword($this->passwordHasher->hashPassword($user1, 'admin'));
            $this->addReference('user1', $user1);

            $manager->persist($user1);

            $user2 = new User();        
            $user2->setEmail('RF@wildseries.com');
            $user2->setRoles(['ROLE_USER']);
            $user2->setPrenom('Roger');
            $user2->setNom('Federer');
            $user2->setPassword($this->passwordHasher->hashPassword($user2, 'contri'));
            $this->addReference('user2', $user2);

            $manager->persist($user2);

            $manager->flush();
    }
}
