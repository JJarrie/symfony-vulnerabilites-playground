<?php

namespace App\Controller\Admin\Users;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/demote/{id}', name: 'admin:demote', methods: ['POST'])]
class DemoteAdminAction extends AbstractController
{
    public function __invoke(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user->roles = [];
        $entityManager->flush();

        return $this->redirectToRoute('admin:users-list');
    }
}