<?php

namespace App\Controller\Admin\Users;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/users', name: 'admin:users-list', methods: ['GET'])]
class UserListAction extends AbstractController
{
    public function __invoke(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users-list.html.twig', [
            'users' => $users,
        ]);
    }
}
