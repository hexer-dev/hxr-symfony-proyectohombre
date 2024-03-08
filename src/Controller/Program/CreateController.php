<?php

namespace App\Controller\Program;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateController extends AbstractController
{
    #[Route('/program/add', name: 'app_program_add')]
    public function add(
        ProgramRepository $repository,
        Request $request
    ): Response {
        $program = new Program();

        $this->denyAccessUnlessGranted(ProgramVoter::ADD, $program);

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program = $form->getData();

            $repository->add($program);

            $this->addFlash('success', sprintf('Programa creado correctamente'));

            return $this->redirectToRoute('app_program_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear el programa.');
        }

        return $this->render('program/add.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }
}
