<?php

namespace App\Tests\Integration\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testSmokeRegistrationRoute(): void {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
    }

    public function testUserCanRegister(): void {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'registration_form[username]' => 'test_username',
            'registration_form[password][first]' => 'test_password',
            'registration_form[password][second]' => 'test_password',
        ]);

        $this->assertResponseRedirects('/login');
    }
}