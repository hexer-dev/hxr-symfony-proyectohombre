<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteUserController extends AbstractController
{
    #[Route('/user/remove/{id}', name: 'app_user_remove')]
    public function remove(
        User $user,
        UserRepository $repository,
        Request $request
    ): Response
    {
        $repository->remove($user);

        $this->addFlash('sucess', sprintf('Profesional eliminado'));

        return $this->redirectToRoute('app_user_list');
    }
}
