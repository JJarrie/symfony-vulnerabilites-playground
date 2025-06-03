<?php

namespace App\Controller\Admin\Users;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/promote/{id}', name: 'admin:promote', methods: ['POST'])]
class PromoteAdminAction extends AbstractController
{
    public function __invoke(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user->roles = array_unique(array_merge($user->getRoles(), ['ROLE_ADMIN']));
        $entityManager->flush();

        return $this->redirectToRoute('admin:users-list');
    }
}
