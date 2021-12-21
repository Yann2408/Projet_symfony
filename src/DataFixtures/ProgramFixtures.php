<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use App\Service\Slugify;
use App\DataFixtures\ActorFixtures;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct( Slugify $slugify)
    {
        $this->slugify =$slugify;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

         for ($i = 0; $i < 20; $i++) {

            $program = new Program ();

            $program->setTitle($faker->words(3, true));
            $program->setSummary($faker->paragraph(6));
            $program->setPoster("https://picsum.photos/200/300?random=" . $faker->numberBetween(1 , 500));
            $program->setCategory($this->getReference('category_' . $faker->numberBetween(0 , 9) ));
            // for ($i=0; $i < count(CategoryFixtures::CATEGORIES); $i++) {
            //     $program->setCategory();
            // }
            $slug = $this->slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setAnneeSortie($faker->numberBetween(2005, 2021));
            for ($j=0; $j < 5; $j++) {
                $program->addActor($this->getReference('actor_' . rand(0 , 49)));
            }
            $this->addReference('program_' . $i, $program);

            $manager->persist($program);
          }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }
}
