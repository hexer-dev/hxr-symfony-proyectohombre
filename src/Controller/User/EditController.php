<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function edit(
        User $user,
        UserPasswordHasherInterface $hasherPassword,
        UserRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if (null !== $user->getPassword() && !empty($user->getPassword())) {
                $passwordHashed = $hasherPassword->hashPassword(
                    $user,
                    $user->getPassword()
                );

                $user->setPassword($passwordHashed);
            }

            $repository->add($user);

            $this->addFlash('success', sprintf('Profesional Actualizado'));

            return $this->redirectToRoute('app_user_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar el profesional.');
        }

        return $this->render('user/edit.html.twig', [
            'entity' => $user,
            'form' => $form->createView()
        ]);
    }
}
