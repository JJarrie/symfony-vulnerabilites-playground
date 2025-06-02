<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const array USERS_FIXTURES = [
        [
            'username' => 'admin',
            'role' => 'ROLE_ADMIN',
            'password' => 'admin',
        ],
        [
            'username' => 'user',
            'role' => 'ROLE_USER',
            'password' => 'user',
        ],
    ];

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS_FIXTURES as $userData) {
            $user = new User();
            $user->username = $userData['username'];
            $user->roles = [$userData['role']];
            $user->password = $this->userPasswordHasher->hashPassword($user, $userData['password']);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
