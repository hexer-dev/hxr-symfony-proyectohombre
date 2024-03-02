<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
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
        $this->denyAccessUnlessGranted(UserVoter::LIST, new User);

        $currentUser = $this->getUser();

        if (in_array('ROLE_SUPER_ADMIN', $currentUser->getRoles())) {
            $usuarios = $repository->findAll();
        } else {
            $usuarios = $repository->withoutSuperAdmin();
        }

        return $this->render('user/index.html.twig', [
            'usuarios' => $usuarios
        ]);
    }
}
