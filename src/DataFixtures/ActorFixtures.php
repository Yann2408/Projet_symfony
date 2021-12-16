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



    // public const ACTOR = array(
    //     array('firstname' => 'Jeffrey', 'lastname' => 'Dean Morgan', 'birth_day' => '1966-04-22'),
    //     array('firstname' => 'Chandler', 'lastname' => 'Riggs', 'birth_day' => '1999-06-27')); 
        
    // public function load(ObjectManager $manager): void
    // {
    //     foreach (self::ACTOR as $key => $a){
    //         $actor = new Actor();
    //         $actor->setFirstName($a['firstname']);
    //         $actor->setLastName($a['lastname']);
    //         $actor->setBirthDate($a['birth_day']);

    //         $this->addReference('actor_' . $key, $actor);

    //         $manager->persist($actor);
    //     }

    //     $manager->flush();
    // }

    
}
