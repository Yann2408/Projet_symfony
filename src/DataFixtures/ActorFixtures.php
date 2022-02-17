<?php

namespace App\DataFixtures;



use Faker\Factory;
use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {

            $actor = new Actor ();

            $actor->setFirstName($faker->firstname());
            $actor->setLastName($faker->lastname());
            $actor->setBirthDate($faker->date('Y_m_d'));
           
            $this->addReference('actor_' . $i, $actor);
            $manager->persist($actor);
        }
        
        $manager->flush();
    } 
}
