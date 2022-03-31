<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\TaskList;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private const TASK_LISTS = ['TaskList A', 'TaskList B', 'TaskList C'];
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user->setEmail('user@gmail.com');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);

        for ($i = 0; $i < count(self::TASK_LISTS); $i++) {
            $list = (new TaskList)
                ->setName(self::TASK_LISTS[$i])
                ->setUser($user);
            $manager->persist($list);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupDev'];
    }
}
