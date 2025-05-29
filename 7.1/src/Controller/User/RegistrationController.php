<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\Type\User\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
class RegistrationController extends AbstractController
{
    public function __invoke(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted()) {
            if ($registrationForm->isValid()) {
                $plainTextPassword = $registrationForm['password']?->getData();
                if (\is_string($plainTextPassword)) {
                    $password = $hasher->hashPassword($user, $plainTextPassword);
                    $user->password = $password;

                    $entityManager->persist($user);
                    $entityManager->flush();
                }
            }
        }

        return $this->render('user/registration.html.twig', [
            'form' => $registrationForm,
        ]);
    }
}
