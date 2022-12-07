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
        $type = $request->query->get("type");

        if (!$user && !$type) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findAll();
        } elseif ($user && !$type) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findBy(['id_user' => intval($user)]);
        } elseif ($type && !$user) {
            $histories = $managerRegistry->getManager()->getRepository(History::class)->findBy(['id_type' => intval($type)]);
        } elseif ($user && $type) {
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
                $user = $request->request->get('user');

                if (!$name || !$brand || !$barcode || !$image || !$type || !$user) {
                    $missing = [];
                    if (!$name) {
                        $missing[] = 'name';
                    }
                    if (!$brand) {
                        $missing[] = 'brand';
                    }
                    if (!$barcode) {
                        $missing[] = 'barcode';
                    }
                    if (!$image) {
                        $missing[] = 'image';
                    }
                    if (!$type) {
                        $missing[] = 'type';
                    }
                    if (!$user) {
                        $missing[] = 'user';
                    }
                    return $this->json([
                        'message' => 'Paramètres manquants',
                        'missing' => $missing,
                    ]);
                }
                $history = new History();
                $history->setName($name);
                $history->setBrand($brand);
                $history->setBarcode($barcode);
                $history->setImage($image);
                $history->setIdType($managerRegistry->getManager()->getRepository(Type::class)->find(intval($type)));
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

    #[Route('/check-barcode', name: 'history_checkbarcode', methods: ['POST'])]
    public function checkBarcode(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        //check si le code barre existe dans la table history
        $barcode = $request->request->get('barcode');
        $user = $request->request->get('user');
        $history = $managerRegistry->getManager()->getRepository(History::class)->findOneBy(['barcode' => $barcode,'id_user'=>$user]);
        if ($history) {
            return $this->json([
                'message' => 'success',
                'history' => [
                    'id' => $history->getId(),
                    'name' => $history->getName(),
                    'brand' => $history->getBrand(),
                    'barcode' => $history->getBarcode(),
                    'image' => $history->getImage(),
                    'type' => $history->getIdType()->getName(),
                    "createdAt" => $history->getCreatedAt()->format('d/m/Y H:i'),
                ]
            ]);
        } else {
            return $this->json([
                'message' => 'Produit non trouvé',
            ]);
        }
    }

    #[Route('/history/{id}', name: 'history_show', methods: ['GET', "POST", "DELETE"])]
    public function show(ManagerRegistry $managerRegistry, Request $request, int $id): JsonResponse
    {
        $method = $request->getMethod();
        switch ($method) {
            case "GET":
                $history = $managerRegistry->getManager()->getRepository(History::class)->findOneBy(['id' => $id]);
                return $this->extracted($history);
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


    #[Route('/historyByUser/{id}', name: 'history_user_count', methods: ['GET'])]
    public function getUserHistory(ManagerRegistry $managerRegistry, Request $request, int $id): JsonResponse
    {
        if($request->query->get("page")) {
            $page = $request->query->get("page");
        } else {
            $page=1;
        }
        $history = $managerRegistry->getManager()->getRepository(History::class)
            ->paginateHistory($page,10,$id);
        return $this->extracted($history);
    }

    #[Route('/productCounts/{id}', name: 'history_user', methods: ['GET'])]
    public function getProductCounts(ManagerRegistry $managerRegistry, int $id): JsonResponse
    {
        $counts = $managerRegistry->getManager()->getRepository(History::class)
            ->countUserProducts($id);
        return $this->json($counts);
    }


    /**
     * @param mixed $history
     * @return JsonResponse
     */
    public function extracted(mixed $history): JsonResponse
    {
        if (!$history) {
            return $this->json([
                'status' => 'error',
                'message' => 'History not found',
            ]);
        }
        if (gettype($history)!=='array') {
            $data = [
                'id' => $history->getId(),
                'name' => $history->getName(),
                'brand' => $history->getBrand(),
                'barcode' => $history->getBarcode(),
                'image' => $history->getImage(),
                'type' => $history->getIdType()->getName(),
                "createdAt" => $history->getCreatedAt()->format('d/m/Y H:i'),
            ];
        } else {
            foreach ($history as $singleHistory) {
                $data[] = [
                    'id' => $singleHistory->getId(),
                    'name' => $singleHistory->getName(),
                    'brand' => $singleHistory->getBrand(),
                    'barcode' => $singleHistory->getBarcode(),
                    'image' => $singleHistory->getImage(),
                    'type' => $singleHistory->getIdType()->getName(),
                    "createdAt" => $singleHistory->getCreatedAt()->format('d/m/Y H:i'),
                    'user' => [
                        'id' => $singleHistory->getIdUser()->getId(),
                        'firstname' => $singleHistory->getIdUser()->getFirstname(),
                        'lastname' => $singleHistory->getIdUser()->getLastname(),
                        'email' => $singleHistory->getIdUser()->getEmail(),
                        'role' => $singleHistory->getIdUser()->getRoles(),
                    ],
                ];
            }
            return $this->json([
                'status' => 'success',
                'histories' => $data,
            ]);
        }

        return $this->json([
            'history' => $data,
        ]);
    }
}
