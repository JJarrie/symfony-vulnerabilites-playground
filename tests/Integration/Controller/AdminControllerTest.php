<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testSmokeAdminRoute(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $admin */
        $admin = $userRepository->findOneBy(['username' => 'admin']);

        $client->loginUser($admin);

        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }
}