<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin')]
class AdminController
{
    public function __invoke(): Response
    {
        return new Response('<h1>Admin area</h1>');
    }
}