<?php

namespace App\Controller;

use App\Entity\Trash;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/paginate', name: 'app_base')]
class PaginateController extends AbstractController
{
    #[Route('/user', name: 'app_paginate')]
    public function index(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $entityManager = $managerRegistry->getManager();
        $method = $request->getMethod();
        if ($method === "GET") {
            $page = $request->query->get("page");
            $limit = $request->query->get("limit");
            $users = $entityManager->getRepository(User::class)->paginateUser($page, $limit);
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'roles' => $user['roles'],
                    "first_name" => $user['first_name'],
                    "last_name" => $user['last_name'],
                    "profil_picture" => $user['profil_pic'],
                    "birthday" => $user["birthday"],
                    "deleted_at" => $user["deleted_at"],
                ];
            }
            return $this->json($data);
        } else {
            return $this->json("Method not allowed");
        }
    }

    #[Route('/trash', name: 'app_paginate_trash')]
    public function paginateTrash(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $entityManager = $managerRegistry->getManager();
        $method = $request->getMethod();
        if ($method === "GET") {
            $page = $request->query->get("page");
            $limit = $request->query->get("limit");
            $trashes = $entityManager->getRepository(Trash::class)->paginateTrash($page, $limit);
            $data = [];
            foreach ($trashes as $trash) {
                $data[] = [
                    'id' => $trash->getId(),
                    'commune' => $trash->getCommune(),
                    'latitude' => $trash->getLatitude(),
                    'longitude' => $trash->getLongitude(),
                    'adresse' => $trash->getAdresse(),
                    'type' => $trash->getIdType()->getName(),
                ];
            }
            return $this->json($data);
        } else {
            return $this->json("Method not allowed");
        }
    }
}
