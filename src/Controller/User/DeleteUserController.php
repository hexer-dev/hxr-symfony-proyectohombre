<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
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
    ): Response {        
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);
        
        $repository->remove($user);

        $this->addFlash('success', sprintf('Profesional eliminado'));

        return $this->redirectToRoute('app_user_list');
    }
}
