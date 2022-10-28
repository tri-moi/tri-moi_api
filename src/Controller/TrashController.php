<?php

namespace App\Controller;

use App\Entity\Trash;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'app_trash')]
class TrashController extends AbstractController
{
    #[Route('/trash', name: 'app_trash')]
    public function index(ManagerRegistry $managerRegistry): JsonResponse
    {

        $trashs = $managerRegistry->getRepository(Trash::class)->findAll();
        $data = [];
        foreach ($trashs as $trash) {
            $data[] = [
                'id' => $trash->getId(),
                "commune" => $trash->getCommune(),
                "latitude" => $trash->getLatitude(),
                "longitude" => $trash->getLongitude(),
                "type" => $trash->getIdType()->getName(),
                "adresse" => $trash->getAdresse(),
            ];
        }
        return $this->json($data);

    }
}
