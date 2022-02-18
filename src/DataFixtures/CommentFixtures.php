<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\EpisodeFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($episodeId = 0; $episodeId < 15; $episodeId++) {
        $episodeReference = $this->getReference('episode_'.$episodeId);

            for ($i = 0; $i < 5; $i++) {
                $comment = new Comment ();

                $comment->setComment($faker->paragraph(6));
                $comment->setRate($faker->numberBetween(0, 10));
                $comment->setAuthor($this->getReference('user2'));
                $comment->setEpisode( $episodeReference);

                $manager->persist($comment);
            }
         }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            EpisodeFixture::class,
        ];
    }
}
