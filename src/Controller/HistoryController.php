<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\Trash;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class HistoryController extends AbstractController
{
    // get all history
    #[Route('/histories', name: 'history', methods: ['GET'])]
    public function index(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $user = $request->query->get('user');
        $trash = $request->query->get("trash");
        $type = $request->query->get("type");


        if (!$user && !$trash && !$type) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findAll();
        } elseif ($user && !$trash && !$type) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findBy(['id_user' => intval($user)]);
        } elseif ($trash && !$user && !$type) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findBy(['id_trash' => intval($trash)]);
        } elseif ($type && !$user && !$trash) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findBy(['id_type' => intval($type)]);
        } elseif ($user && $type && !$trash) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findBy(['id_user' => intval($user), 'id_type' => intval($type)]);
        } else {
            return $this->json([
                'message' => 'error',
            ]);
        }

        $data = [];
        foreach ($histories as $history) {
            $data[] = [
                'id' => $history->getId(),
                'name' => $history->getName(),
                'brand' => $history->getBrand(),
                'barcode' => $history->getBarcode(),
                'image' => $history->getImage(),
                'type' => $history->getIdType()->getName(),
                "createdAt" => $history->getCreatedAt()->format('d/m/Y H:i'),
                'trash' => [
                    'id' => $history->getIdTrash()->getId(),
                    'commune' => $history->getIdTrash()->getCommune(),
                    'latitude' => $history->getIdTrash()->getLatitude(),
                    'longitude' => $history->getIdTrash()->getLongitude(),
                    'adresse' => $history->getIdTrash()->getAdresse(),
                    'type' => $history->getIdTrash()->getIdType()->getName(),
                ],
                'user' => [
                    'id' => $history->getIdUser()->getId(),
                    'firstname' => $history->getIdUser()->getFirstname(),
                    'lastname' => $history->getIdUser()->getLastname(),
                    'email' => $history->getIdUser()->getEmail(),
                    'role' => $history->getIdUser()->getRoles(),
                ],
            ];
        }
        return $this->json([
            'histories' => $data,
        ]);
    }

    #[Route('/history', name: 'history_crud', methods: ['POST'])]
    public function post(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $method = $request->getMethod();
        switch ($method) {
            case 'POST':
                $name = $request->request->get('name');
                $brand = $request->request->get('brand');
                $barcode = $request->request->get('barcode');
                $image = $request->request->get('image');
                $type = $request->request->get('type');
                $trash = $request->request->get('trash');
                $user = $request->request->get('user');

                if (!$name || !$brand || !$barcode || !$image || !$type || !$trash || !$user) {
                    return $this->json([
                        'message' => 'Missing parameters',
                    ]);
                }
                $history = new History();
                $history->setName($name);
                $history->setBrand($brand);
                $history->setBarcode($barcode);
                $history->setImage($image);
                $history->setIdType($managerRegistry->getManager()->getRepository(Type::class)->find(intval($type)));
                $history->setIdTrash($managerRegistry->getManager()->getRepository(Trash::class)->find(intval($trash)));
                $history->setIdUser($managerRegistry->getManager()->getRepository(User::class)->find(intval($user)));
                $history->setCreatedAt(new \DateTimeImmutable());

                $managerRegistry->getManager()->persist($history);
                $managerRegistry->getManager()->flush();

                return $this->json([
                    'message' => 'success',
                ]);
                break;
            default:
                return $this->json([
                    'message' => 'error',
                ]);
                break;
        }
    }

    #[Route('/history/{id}', name: 'history_show', methods: ['GET',"POST", "DELETE"])]
    public function show(ManagerRegistry $managerRegistry, Request $request, int $id): JsonResponse
    {
        $method = $request->getMethod();
        switch ($method) {
            case "GET":
                $history = $managerRegistry->getManager()->getRepository(History::class)->findOneBy(['id' => $id]);
                if (!$history) {
                    return $this->json([
                        'message' => 'History not found',
                    ]);
                }
                $data = [
                    'id' => $history->getId(),
                    'name' => $history->getName(),
                    'brand' => $history->getBrand(),
                    'barcode' => $history->getBarcode(),
                    'image' => $history->getImage(),
                    'type' => $history->getIdType()->getName(),
                    "createdAt" => $history->getCreatedAt()->format('d/m/Y H:i'),
                    'trash' => [
                        'id' => $history->getIdTrash()->getId(),
                        'commune' => $history->getIdTrash()->getCommune(),
                        'latitude' => $history->getIdTrash()->getLatitude(),
                        'longitude' => $history->getIdTrash()->getLongitude(),
                        'adresse' => $history->getIdTrash()->getAdresse(),
                        'type' => $history->getIdTrash()->getIdType()->getName(),
                    ],
                ];
                return $this->json([
                    'history' => $data,
                ]);
                break;

            case "POST":
                $query = $request->query->get('_method');
                if ($query === "PUT") {
                    $name = $request->request->get('name');
                    $brand = $request->request->get('brand');
                    $barcode = $request->request->get('barcode');
                    $image = $request->request->get('image');
                    $type = $request->request->get('type');
                    $trash = $request->request->get('trash');
                    $user = $request->request->get('user');

                    if (!$name || !$brand || !$barcode || !$image || !$type || !$trash || !$user) {
                        return $this->json([
                            'message' => 'Missing parameters',
                        ]);
                    }
                    $history = $managerRegistry->getManager()->getRepository(History::class)->find($id);
                    $history->setName($name);
                    $history->setBrand($brand);
                    $history->setBarcode($barcode);
                    $history->setImage($image);
                    $history->setIdType($managerRegistry->getManager()->getRepository(Type::class)->find(intval($type)));
                    $history->setIdTrash($managerRegistry->getManager()->getRepository(Trash::class)->find(intval($trash)));
                    $history->setIdUser($managerRegistry->getManager()->getRepository(User::class)->find(intval($user)));

                    $managerRegistry->getManager()->persist($history);
                    $managerRegistry->getManager()->flush();

                    return $this->json([
                        'message' => 'success',
                    ]);
                } else {
                    return $this->json([
                        'message' => 'erroaaar',
                    ]);
                }
                break;
            case "DELETE":
                $history = $managerRegistry->getManager()->getRepository(History::class)->find($id);
                $managerRegistry->getManager()->remove($history);
                $managerRegistry->getManager()->flush();
                return $this->json([
                    'message' => 'success',
                ]);
                break;
            default:
                return $this->json([
                    'message' => 'method not allowed',
                ]);
                break;
        }
    }
}
