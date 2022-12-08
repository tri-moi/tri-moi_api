<?php

namespace App\Controller;
use App\Entity\UserBadge;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BadgeController extends AbstractController
{

    // get all history
    #[Route('/badges', name: 'badges', methods: ['GET'])]
    public function getBadges(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $user = $request->query->get('user');

        if (!$user) {
            $badges = $managerRegistry->getManager()->getRepository(UserBadge::class)->findAll();
        } else {
            $badges = $managerRegistry->getManager()->getRepository(UserBadge::class)->findBy(['id_user' => intval($user)]);
        }

        $data = [];
        foreach ($badges as $badge) {
            $data[] = [
                'id' => $badge->getId(),
                'badge' => json_decode($badge->getBadge()),
                'level' => json_decode($badge->getLevel()),
                'scanNumber' => json_decode($badge->getNmbreScan()),
                'user' => [
                    'id' => $badge->getIdUser()->getId(),
                    'firstname' => $badge->getIdUser()->getFirstname(),
                    'lastname' => $badge->getIdUser()->getLastname(),
                    'email' => $badge->getIdUser()->getEmail(),
                    'role' => $badge->getIdUser()->getRoles(),
                ],
            ];
        }
        return $this->json([
            'badges' => $data,
        ]);
    }
}
