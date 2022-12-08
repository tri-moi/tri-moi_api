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
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName(),
                "profilePicture" => $user->getProfilPic(),
                "birthDate" => $user->getBirthday(),
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
                        "firstName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "profilePicture" => $user->getProfilPic(),
                        "birthDate" => $user->getBirthday(),
                        "createdAt" => $user->getCreatedAt(),
                        "updatedAt" => $user->getUpdatedAt(),
                    ];
                    return $this->json($data);
                    break;
                case "POST":
                    if ($request->query->get("_method")) {
                        $method = $request->query->get("_method");
                        if ($method === "PUT") {
                            $type = $request->request->get("type");
                            switch ($type) {
                                case "firstName":
                                    $user->setFirstName($request->request->get("firstName"));
                                    $user->setUpdatedAt(new \DateTimeImmutable("now"));
                                    $data = [
                                        'id' => $user->getId(),
                                        'email' => $user->getEmail(),
                                        'roles' => $user->getRoles(),
                                        "firstName" => $user->getFirstName(),
                                        "lastName" => $user->getLastName(),
                                        "profilePicture" => $user->getProfilPic(),
                                        "birthDate" => $user->getBirthday(),
                                        "createdAt" => $user->getCreatedAt(),
                                        "updatedAt" => $user->getUpdatedAt(),
                                    ];

                                    $managerRegistry->getManager()->flush();
                                    return $this->json([
                                        "success" => true,
                                        "message" => "First name updated",
                                        "data" => $data
                                    ]);
                                    break;
                                case "lastName":
                                    $user->setLastName($request->request->get("lastName"));
                                    $user->setUpdatedAt(new \DateTimeImmutable("now"));
                                    $managerRegistry->getManager()->flush();
                                    $data = [
                                        'id' => $user->getId(),
                                        'email' => $user->getEmail(),
                                        'roles' => $user->getRoles(),
                                        "firstName" => $user->getFirstName(),
                                        "lastName" => $user->getLastName(),
                                        "profilePicture" => $user->getProfilPic(),
                                        "birthDate" => $user->getBirthday(),
                                        "createdAt" => $user->getCreatedAt(),
                                        "updatedAt" => $user->getUpdatedAt(),
                                    ];

                                    return $this->json([
                                        "success" => true,
                                        "message" => "Last name updated",
                                        "data" => $data
                                    ]);
                                    break;
                                case "email":
                                    $user->setEmail($request->request->get("email"));
                                    $user->setUpdatedAt(new \DateTimeImmutable("now"));
                                    $managerRegistry->getManager()->flush();
                                    $data = [
                                        'id' => $user->getId(),
                                        'email' => $user->getEmail(),
                                        'roles' => $user->getRoles(),
                                        "firstName" => $user->getFirstName(),
                                        "lastName" => $user->getLastName(),
                                        "profilePicture" => $user->getProfilPic(),
                                        "birthDate" => $user->getBirthday(),
                                        "createdAt" => $user->getCreatedAt(),
                                        "updatedAt" => $user->getUpdatedAt(),
                                    ];

                                    return $this->json([
                                        "success" => true,
                                        "message" => "Birthday updated",
                                        "data" => $data
                                    ]);
                                    break;
                                case "birthDate":
                                    $user->setBirthday(new \DateTime($request->request->get("birthDate")));
                                    $user->setUpdatedAt(new \DateTimeImmutable("now"));
                                    $managerRegistry->getManager()->flush();
                                    $data = [
                                        'id' => $user->getId(),
                                        'email' => $user->getEmail(),
                                        'roles' => $user->getRoles(),
                                        "firstName" => $user->getFirstName(),
                                        "lastName" => $user->getLastName(),
                                        "profilePicture" => $user->getProfilPic(),
                                        "birthDate" => $user->getBirthday(),
                                        "createdAt" => $user->getCreatedAt(),
                                        "updatedAt" => $user->getUpdatedAt(),
                                    ];

                                    return $this->json([
                                        "success" => true,
                                        "message" => "Birthday updated",
                                        "data" => $data
                                    ]);
                                    break;
                                case "profilePicture":
                                    // receive the file
                                    $files = $request->files->get("file");
                                    $publicResourcesFolderPath = $this->getParameter('kernel.project_dir') . '/public/uploads/';
                                    $fileName = "profil_pic_" . $user->getId() . ".jpg";
                                    $link = $publicResourcesFolderPath . $fileName;
                                    if (file_exists($link)) {
                                        unlink($link);
                                    }
                                    move_uploaded_file($files->getpathName(), $link);

                                    $user->setProfilPic($fileName);
                                    $user->setUpdatedAt(new \DateTimeImmutable("now"));
                                    $managerRegistry->getManager()->flush();
                                    $data = [
                                        'id' => $user->getId(),
                                        'email' => $user->getEmail(),
                                        'roles' => $user->getRoles(),
                                        "firstName" => $user->getFirstName(),
                                        "lastName" => $user->getLastName(),
                                        "profilePicture" => $user->getProfilPic(),
                                        "birthDate" => $user->getBirthday(),
                                        "createdAt" => $user->getCreatedAt(),
                                        "updatedAt" => $user->getUpdatedAt(),
                                    ];

                                    return $this->json([
                                        "success" => true,
                                        "message" => "Profile picture updated",
                                        "data" => $data
                                    ]);
                                    break;
                                case "password":
                                    $user->setPassword($request->request->get("password"));
                                    $user->setUpdatedAt(new \DateTimeImmutable("now"));
                                    $managerRegistry->getManager()->flush();
                                    $data = [
                                        'id' => $user->getId(),
                                        'email' => $user->getEmail(),
                                        'roles' => $user->getRoles(),
                                        "firstName" => $user->getFirstName(),
                                        "lastName" => $user->getLastName(),
                                        "profilePicture" => $user->getProfilPic(),
                                        "birthDate" => $user->getBirthday(),
                                        "createdAt" => $user->getCreatedAt(),
                                        "updatedAt" => $user->getUpdatedAt(),
                                    ];

                                    return $this->json([
                                        "success" => true,
                                        "message" => "Password updated",
                                        "data" => $data
                                    ]);
                                    break;
                                default:
                                    return $this->json([
                                        "success" => false,
                                        "message" => "Type not found"
                                    ]);
                                    break;
                            }
                        } else {
                            return $this->json(["message" => "method not allowed"]);
                        }
                    } else {
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
