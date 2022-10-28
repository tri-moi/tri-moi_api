<?php

namespace App\Controller;

use App\Entity\Trash;
use App\Entity\Type;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'app_trash')]
class TrashController extends AbstractController
{
    #[Route('/trash', name: 'app_trash')]
    public function index(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $entityManager = $managerRegistry->getManager();
        $method = $request->getMethod();

        switch ($method) {
            case "GET":
                $trashes = $entityManager->getRepository(Trash::class)->findAll();
                $data = [];
                foreach ($trashes as $trash) {
                    $data[] = [
                        'id' => $trash->getId(),
                        'commune' => $trash->getCommune(),
                        'latitude' => $trash->getLatitude(),
                        'longitude' => $trash->getLongitude(),
                        'adresse' => $trash->getAdresse(),
                        'id_type' => $trash->getIdType()->getId(),
                    ];
                }
                return new JsonResponse($data);
                break;
            case "POST":
                $commune = $request->request->get("commune");
                $latitude = $request->request->get("latitude");
                $longitude = $request->request->get("longitude");
                $adresse = $request->request->get("adresse");
                if ($commune && $latitude && $longitude && $adresse) {
                    $trash = new Trash();
                    $trash->setCommune(ucfirst($commune));
                    $trash->setLatitude($latitude);
                    $trash->setLongitude($longitude);
                    $trash->setAdresse($adresse);
                    $trash->setIdType($managerRegistry->getRepository(Type::class)->findOneBy(["id" => $request->request->get("type")]));
                    $manager = $managerRegistry->getManager();
                    $manager->persist($trash);
                    $manager->flush();
                    return $this->json("Trash created");
                } else {
                    return $this->json("Missing parameters");
                }
                break;
            default:
                return $this->json("Method not allowed");
        }
    }

    #[Route('/trash/{id}', name: 'app_trash_one')]
    public function show($id, ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();
        switch ($method) {
            case "GET":
                $trash = $managerRegistry->getRepository(Trash::class)->find($id);
                $data = [
                    'id' => $trash->getId(),
                    "commune" => $trash->getCommune(),
                    "latitude" => $trash->getLatitude(),
                    "longitude" => $trash->getLongitude(),
                    "type" => $trash->getIdType()->getName(),
                    "adresse" => $trash->getAdresse(),
                ];
                return $this->json($data);
                break;
            case "DELETE":
                $trash = $managerRegistry->getRepository(Trash::class)->find($id);
                $manager = $managerRegistry->getManager();
                $manager->remove($trash);
                $manager->flush();
                return $this->json("Trash deleted");
                break;
            case "POST":
                $query = $request->query->get("_method");
                if ($query == "PUT") {
                    $commune = $request->request->get("commune");
                    $latitude = $request->request->get("latitude");
                    $longitude = $request->request->get("longitude");
                    $adresse = $request->request->get("adresse");
                    $type = $managerRegistry->getRepository(Type::class)->findOneBy(["id" => $request->request->get("type")]);
                    if ($commune && $latitude && $longitude && $adresse && $type) {
                        $trash = $managerRegistry->getRepository(Trash::class)->find($id);
                        $trash->setCommune(ucfirst($commune));
                        $trash->setLatitude($latitude);
                        $trash->setLongitude($longitude);
                        $trash->setAdresse($adresse);
                        $trash->setIdType($type);
                        $manager = $managerRegistry->getManager();
                        $manager->persist($trash);
                        $manager->flush();
                        return $this->json("Trash updated");
                    } else {
                        return $this->json("Missing parameters");
                    }
                } else {
                    return $this->json("Method not allowed");
                }
                break;

            default:
                return $this->json("Method not allowed");
                break;
        }

    }
}
