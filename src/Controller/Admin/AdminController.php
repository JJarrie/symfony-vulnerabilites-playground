<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin:index')]
class AdminController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
