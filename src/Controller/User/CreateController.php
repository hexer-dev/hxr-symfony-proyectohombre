<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateController extends AbstractController
{
    #[Route('/user/add', name: 'app_user_add', methods: ['GET', 'POST'])]
    public function add(
        UserPasswordHasherInterface $hasherPassword,
        UserRepository $repository,
        Request $request
    ): Response {
        $user = new User();

        $this->denyAccessUnlessGranted(UserVoter::ADD, $user);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $form->get('password')->getData();

            if (null === $password || empty($password)) {
                $this->addFlash('danger', 'Debe insertar una contraseña');
                
                return $this->render('user/add.html.twig', [
                    'entity' => $user,
                    'form' => $form->createView()
                ]);
            }

            $passowordHashed = $hasherPassword->hashPassword(
                $user,
                $password
            );

            $user->setPassword($passowordHashed);

            $repository->add($user);

            $this->addFlash('success', sprintf('Profesional de la aplicación creado'));

            return $this->redirectToRoute('app_user_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear el profesional.');
        }

        return $this->render('user/add.html.twig', [
            'entity' => $user,
            'form' => $form->createView()
        ]);
    }
}
