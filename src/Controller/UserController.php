<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_list')]
    public function list(
        UserRepository $repository, 
        Request $request): Response
    {
        $usuarios = $repository->findAll();

       return $this->render('user/index.html.twig', [
            'usuarios' => $usuarios
        ]);
    }

    #[Route('/user/add', name: 'app_user_add', methods: ['GET', 'POST'])]
    public function add(
        UserPasswordHasherInterface $hasherPassword,
        UserRepository $repository,
        Request $request
    ): Response
    {
        //Comprobamos los permisos
        //$this->denyAccessUnlessGranted($action->getPermission(), $entity);        

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $passowordHashed = $hasherPassword->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($passowordHashed);

            $repository->add($user);
            
            $this->addFlash('sucess', sprintf('Profesional de la aplicación creado'));

            return $this->redirectToRoute('app_user_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear el profesional.');
        }

        return $this->render('user/add.html.twig', [
            'entity' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function edit(
        User $user,
        UserPasswordHasherInterface $hasherPassword,
        UserRepository $repository,
        Request $request
    ): Response
    {
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
        
            $this->addFlash('sucess', sprintf('Profesional Actualizado'));

            return $this->redirectToRoute('app_user_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar el profesional.');
        }

        return $this->render('user/edit.html.twig', [
            'entity' => $user,
            'form' => $form->createView()
        ]);
    }


    #[Route('/user/profile', name: 'app_user_profile')]
    public function profile(
        UserRepository $repository,
        Request $request
    ): Response
    {
        $user =  $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }
}
