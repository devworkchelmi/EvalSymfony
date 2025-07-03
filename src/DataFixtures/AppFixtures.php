<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Board;
use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Utilisateur de test
        $user = new User();
        $user->setEmail('test@insider.fr')
            ->setRoles(['ROLE_INSIDER'])
            ->setUsername('TestUser')
            ->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);

        // Catégorie + board
        $category = new Category();
        $category->setName('Développement')
                 ->setAllowedRoles(['ROLE_INSIDER']);
        $manager->persist($category);

        $board = new Board();
        $board->setName('Symfony')
              ->setCategory($category);
        $manager->persist($board);

        // Topic avec messages
        for ($i = 0; $i < 3; $i++) {
            $topic = new Topic();
            $topic->setTitle($faker->sentence(4))
                  ->setContent($faker->paragraph())
                  ->setAuthor($user)
                  ->setBoard($board);

            $manager->persist($topic);

            for ($j = 0; $j < 2; $j++) {
                $message = new Message();
                $message->setContent($faker->paragraph())
                        ->setAuthor($user)
                        ->setTopic($topic);
                $manager->persist($message);
            }
        }

        $manager->flush();
    }
}