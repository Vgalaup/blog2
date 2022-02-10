<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        $me = new User;
        $me->setName($faker->lastName);
        $me->setEmail('valentin.galaup@gmail.com');
        $me->setPassword($this->hasher->hashPassword($me, '123456'));
        $manager->persist($me);

        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $user = new User;
            $user->setName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($this->hasher->hashPassword($user, '123456'));
            $users[] = $user;
            $manager->persist($user);
        }
        $categories = [];

        for ($i = 1; $i <= 5; $i++) {
            $category = new Category;
            $category->setName($faker->realText($maxNbChars = 30));
            $category->setDescription($faker->realText($maxNbChars = 100));
            $categories[] = $category;
            $manager->persist($category);
        }


        for ($i = 1; $i <= 100; $i++) {
            $post = new Post;
            $post->setTitle($faker->realText($maxNbChars = 50));
            $post->setDate($faker->DateTime($max = 'now'));
            $post->setContent($faker->realText($maxNbChars = rand(50, 5000)));
            $post->setLikes(rand(0, 1000));
            $post->setAuthor($users[rand(0, 19)]);
            $post->setCategory($categories[rand(0, 4)]);
            $manager->persist($post);
        }
        $manager->flush();
    }
}
