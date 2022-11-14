<?php

namespace App\Controller;

use App\Entity\Type;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class TypeController extends AbstractController
{
    #[Route('/types', name: 'app_type')]
    public function index(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();
        if ($method === "GET") {
            $types = $managerRegistry->getRepository(Type::class)->findAll();
            $data = [];
            foreach ($types as $type) {
                $data[] = [
                    'id' => $type->getId(),
                    'name' => $type->getName(),
                ];
            }
            return $this->json($data);
        } else {
            return $this->json([
                'message' => 'method not allowed',
            ]);
        }
    }

    #[Route('/type', name: 'app_type_create')]
    public function create(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();
        if ($method === "POST") {
            $name = $request->get('name');
            $verif_name = $managerRegistry->getRepository(Type::class)->findOneBy(['name' => $name]);
            if ($verif_name) {
                return $this->json([
                    'message' => 'type already exist',
                ]);
            } else {
                $type = new Type();
                $type->setName($name);
                $managerRegistry->getManager()->persist($type);
                $managerRegistry->getManager()->flush();
                return $this->json([
                    'message' => 'type created',
                ]);
            }
        } else {
            return $this->json([
                'message' => 'method not allowed',
            ]);
        }
    }

    #[Route('/type/{id}', name: 'app_type_id')]
    public function oneType($id, ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();

        switch ($method) {
            case "GET":
                $type = $managerRegistry->getRepository(Type::class)->findOneBy(['id' => $id]);
                if ($type) {
                    $data = [
                        'id' => $type->getId(),
                        'name' => $type->getName(),
                    ];
                }
                return $this->json($data ?? ["error" => "Type not found"]);
                break;
            case "POST":
                $query = $request->query->get("_method");
                if ($query) {
                    if ($query === "PUT") {
                        $type = $managerRegistry->getRepository(Type::class)->findOneBy(['id' => $id]);
                        if ($type) {
                            $name = $request->request->get("name");
                            if ($name) {
                                $type->setName($name);
                                $managerRegistry->getManager()->flush();
                                return $this->json([
                                    "message" => "Type updated",
                                ]);
                            } else {
                                return $this->json([
                                    "error" => "Name is required",
                                ]);
                            }
                        } else {
                            return $this->json([
                                "error" => "Type not found",
                            ]);
                        }
                    } else {
                        return $this->json([
                            "error" => "Method not allowed",
                        ]);
                    }
                } else {
                    return $this->json([
                        "error" => "Method not allowed",
                    ]);
                    break;
                }
            case "DELETE":
                $type = $managerRegistry->getRepository(Type::class)->findOneBy(['id' => $id]);
                if ($type) {
                    $managerRegistry->getManager()->remove($type);
                    $managerRegistry->getManager()->flush();
                    return $this->json([
                        "message" => "Type deleted",
                    ]);
                } else {
                    return $this->json([
                        "error" => "Type not found",
                    ]);
                }
                break;
            default:
                return $this->json([
                    "error" => "Method not allowed",
                ]);
                break;
        }
    }
}
