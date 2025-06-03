<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WhoAmIControllerTest extends WebTestCase
{
    public function testSmokeWhoAmIRoute(): void {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => 'user']);

        $client->loginUser($user);

        $client->request('GET', '/who-am-i');
        $this->assertResponseIsSuccessful();
    }
}