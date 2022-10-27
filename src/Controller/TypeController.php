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
    #[Route('/type', name: 'app_type')]
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
                "message" => "Method not allowed",
            ]);
        }
    }

    #[Route('/type/{id}', name: 'app_type_id')]
    public function oneType($id, ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();
        if ($method === "GET") {
            $type = $managerRegistry->getRepository(Type::class)->findOneBy(['id' => $id]);
            if ($type) {
                $data = [
                    'id' => $type->getId(),
                    'name' => $type->getName(),
                ];
            }
            return $this->json($data ?? ["error" => "Type not found"]);
        } else {
            return $this->json([
                "message" => "Method not allowed",
            ]);
        }
    }
}
