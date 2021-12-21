<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\SeasonFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($seasonId = 0; $seasonId < 200; $seasonId++) {
            $seasonReference = $this->getReference('season_' . $seasonId);

            for ($i = 0; $i < 15; $i++) {

                $episode = new Episode ();

                $episode->setNumber($i + 1);
                $episode->setTitle($faker->words(3, true));
                $episode->setSummary($faker->paragraph(6));
                $episode->setSeason($seasonReference);

                $manager->persist($episode);
            }
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont EpisodeFixtures dépend
        return [
          SeasonFixture::class,
        ];
    }
}

