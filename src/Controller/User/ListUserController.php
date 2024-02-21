<?php

namespace App\Controller\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListUserController extends AbstractController
{
    #[Route('/user', name: 'app_user_list')]
    public function list(
        UserRepository $repository,
        Request $request
    ): Response {
        $usuarios = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'usuarios' => $usuarios
        ]);
    }
}
