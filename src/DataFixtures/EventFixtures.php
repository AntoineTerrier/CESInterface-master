<?php

namespace App\DataFixtures;

use App\Entity\Vote;
use App\Entity\Event;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Register;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 10 ; $i++) { 
            $event = new Event();

            $event->setTitle($faker->sentence())
                  ->setDescription($faker->paragraph())
                  ->setIdAuthor($faker->numberBetween(1,5))
                  ->setCreatedAt($faker->dateTimeBetween('-1 years'));
            
            $days = (new \DateTime())->diff($event->getCreatedAt())->days;

            $event->setEventDate($faker->dateTimeBetween('-' . $days . ' days', '+4 months'));

            $manager->persist($event);

            for ($j = 0; $j <= $faker->numberBetween(2, 20); $j++) { 
                $register = new Register();
                $register->setIdUser($faker->numberBetween(1, 50))
                         ->setEvent($event);
                $manager->persist($register);
            }
            for ($j = 0; $j <= $faker->numberBetween(2, 30); $j++) { 
                $vote = new Vote();
                $vote->setIdUser($faker->numberBetween(1, 50))
                      ->setEvent($event);
                $manager->persist($vote);
            }
            for ($j = 0; $j <= $faker->numberBetween(1, 5); $j++) { 
                $picture = new Picture();
                $picture->setIdPublisher($faker->numberBetween(1, 50))
                        ->setEvent($event)
                        ->setPicture($faker->imageUrl(1280,960));
                $manager->persist($picture);

                for ($k = 0; $k <= $faker->numberBetween(2, 4); $k++) { 
                    $comment = new Comment();
                    $comment->setIdUser($faker->numberBetween(1, 50))
                            ->setPicture($picture)
                            ->setContent($faker->paragraph())
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days', '+4 months'));
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
