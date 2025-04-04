<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/super-admin', name: 'app_super_admin')]
class SuperAdminController
{
    public function __invoke(): Response
    {
        return new Response('<h1>Super Admin area</h1>');
    }
}