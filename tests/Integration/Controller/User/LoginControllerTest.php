<?php

namespace App\Tests\Integration\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class LoginControllerTest extends WebTestCase
{
    public function testSmokeLoginRoute(): void {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testUserLoginRoute(): void {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Log in', [
            '_username' => 'user',
            '_password' => 'user',
            '_remember_me' => 'on',
        ]);

        $this->assertResponseRedirects('/');
    }
}