<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_a')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user')]
    public function index(ManagerRegistry $managerRegistry): JsonResponse
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = [
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
        return $this->json($data);
    }

    /**
     * @throws Exception
     */
    #[Route('/user/{id}', name: 'app_user_id')]
    public function user($id, ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();
        $user = $managerRegistry->getRepository(User::class)->find($id);
        if ($user) {
            switch ($method) {
                case "GET":
                    $user = $managerRegistry->getRepository(User::class)->find($id);
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
                    return $this->json($data);
                    break;
                case "POST":
                    if ($request->query->get("_method")) {
                        $method = $request->query->get("_method");
                        if ($method === "PUT") {
                            $user = $managerRegistry->getRepository(User::class)->find($id);
                            $user->setFirstName($request->request->get("first_name"));
                            $user->setLastName($request->request->get("last_name"));
                            $user->setBirthday(new \DateTime($request->request->get("birthday")));
                            $user->setProfilPic($request->request->get("profile_picture"));
                            $user->setUpdatedAt(new \DateTimeImmutable("now"));
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
                            return $this->json($data);
                        } else {
                            return $this->json(["message" => "method not allowed"]);
                        }
                    } else {
                        var_dump("ici");
                        return $this->json(["message" => "method not allowed"]);
                    }
                    break;
                case "DELETE":
                    $user = $managerRegistry->getRepository(User::class)->find($id);
                    $user->setDeletedAt(new \DateTimeImmutable("now"));
                    $managerRegistry->getManager()->flush();
                    return $this->json([
                        'message' => 'user deleted',
                    ]);
                    break;
                default:
                    return $this->json([
                        'message' => 'method not allowed',
                    ]);
                    break;
            }
        } else {
            return $this->json([
                'message' => 'user not found',
            ]);
        }

    }
}
