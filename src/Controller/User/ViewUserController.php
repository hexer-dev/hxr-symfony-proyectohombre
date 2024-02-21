<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewUserController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function profile(
        Request $request
    ): Response {
        $user =  $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }
}
