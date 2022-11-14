<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_base')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'api symfony',
            "access" => [
                "login" => "/api/login",
                "register" => "/api/register",
                "check-mail" => "/api/check-mail",
            ],
        ]);
    }
}
