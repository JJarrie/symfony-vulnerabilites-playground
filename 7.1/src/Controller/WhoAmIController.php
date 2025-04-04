<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'who_am_i')]
class WhoAmIController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('who_ami.html.twig');
    }
}