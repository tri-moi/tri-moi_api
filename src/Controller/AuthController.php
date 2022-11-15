<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserBadge;
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
        $success = false;
        $email = $request->request->get('email');
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
                if ($user->getDeletedAt() != null) {
                    $user->setDeletedAt(null);
                    $managerRegistry->getManager()->flush();
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
                    return $this->json([
                        'account' => "Your account has been reactivated!",
                        'data' => $data,
                    ]);
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
                        $success = true;
                    }
                }
            }

        }
        return $this->json([
            'message' => 'Login',
            'error' => $error,
            "success" => $success,
            "user" => $data,
        ]);
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $managerRegistry): JsonResponse
    {
        $error = [];
        $email = $request->request->get("email");
        $password = $request->request->get("password");
        $first_name = $request->request->get("first_name");
        $last_name = $request->request->get("last_name");
        if (!$email || !$password || !$first_name || !$last_name) {
            if (!$email) {
                $error["email"] = "Email is required";
            }
            if (!$password) {
                $error["password"] = "Password is required";
            }
            if (!$first_name) {
                $error["first_name"] = "First name is required";
            }
            if (!$last_name) {
                $error["last_name"] = "Last name is required";
            }
        } else {
            $user = $managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $error["email"] = "Email already exists";
            } else {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($passwordHasher->hashPassword($user, $password));
                $user->setFirstName($first_name);
                $user->setLastName($last_name);
                $user->setRoles(["ROLE_USER"]);
                $user->setCreatedAt(new \DateTimeImmutable());
                $managerRegistry->getManager()->persist($user);
                $managerRegistry->getManager()->flush();

                // create badges
                $badges = file_get_contents("../src/data/badge.json");
                $badges = json_decode($badges, true);
                $level = file_get_contents("../src/data/level.json");
                $level = json_decode($level, true);
                $user = $managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]);

                foreach ($badges as $item) {
                    foreach ($level as $value) {
                        $managerRegistry->getRepository(UserBadge::class)->addUserBadge($user, $item, $value, 0);

                    }
                }
                return $this->json([
                    "success" => true,
                    'message' => "user and badges created",
                ]);
            }
        }
        return $this->json([
            "success" => false,
            'error' => $error,
        ]);
    }
}

