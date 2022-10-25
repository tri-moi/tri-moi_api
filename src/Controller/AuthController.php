<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    #[Route('/check-mail', name: 'app_check_mail', methods: ['POST'])]
    public function checkMail(Request $request, ManagerRegistry $managerRegistry): JsonResponse
    {
        $email = $request->request->get('email');

        $user = $managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]);

        return $this->json(['exists' => $user !== null]);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $managerRegistry): JsonResponse
    {
        $email = $request->request->get('email');
        $user = $managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]);
        $password = $request->request->get('password');
        $error = null;
        $data = [];
        if (!$email || !$password) {
            $error = "Email and password are required";
        } else {
            $user = $managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user) {
                $error = "User not found";
            } else {
                if (!$passwordHasher->isPasswordValid($user, $password)) {
                    $error = "Invalid credentials";
                } else {
                    $data = [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'roles' => $user->getRoles(),
                        "firtName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "profilePicture" => $user->getProfilPic(),
                        "birthday" => $user->getBirthday(),
                        "createdAt" => $user->getCreatedAt(),
                        "updatedAt" => $user->getUpdatedAt(),
                    ];
                }
            }

        }
        return $this->json([
            'message' => 'Login',
            'error' => $error,
            "user" => $data,
        ]);
    }
}
