<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/who-am-i', name: 'who_am_i', methods: ['GET'])]
#[Route('/', name: 'app_home', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class WhoAmIController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('who_ami.html.twig');
    }
}
