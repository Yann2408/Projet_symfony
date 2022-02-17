<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($programId = 0; $programId < 20; $programId++) {
            $programReference = $this->getReference('program_'.$programId);

            for ($i = 0; $i < 10 ; $i++) {
                $season = new Season();

                $season->setNumber($i + 1);
                $season->setYear($faker->numberBetween(2005, 2021));
                $season->setDescription($faker->paragraph(6));
                $season->setName($faker->words(3, true));
                $season->setProgram($programReference);
                $seasonId = (10 * $programId) + $i;
                $this->addReference(sprintf('season_%s', $seasonId), $season);
                $manager->persist($season);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
          ProgramFixtures::class,
        ];
     }

}
