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
            "user" => [
                "get all users" => "/api/users --> GET",
                "get one user" => "/api/user/{id} --> GET",
                "update user" => "/api/user/{id}?_method=PUT --> POST",
                "delete user" => "/api/user/{id} --> DELETE",
                "paginate user" => "/api/paginate/user?page={int}&limit={int} --> GET",
            ],
            "type" => [
                "get all types" => "/api/type --> GET",
                "get one type" => "/api/type/{id} --> GET",
                "create type" => "/api/type --> POST",
                "update type" => "/api/type/{id}?_method=PUT --> POST",
                "delete type" => "/api/type/{id} --> DELETE",
            ],
            "trash" => [
                "get all trashes" => "/api/trash --> GET",
                "get one trash" => "/api/trash/{id} --> GET",
                "create trash" => "/api/trash --> POST",
                "update trash" => "/api/trash/{id}?_method=PUT --> POST",
                "delete trash" => "/api/trash/{id} --> DELETE",
                "paginate trash" => "/api/paginate/trash?page={int}&limit={int} --> GET",
            ],
        ]);
    }
}
