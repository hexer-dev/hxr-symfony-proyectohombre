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

class EditController extends AbstractController
{
    #[Route('/program/edit/{id}', name: 'app_program_edit')]
    public function edit(
        Program $program,
        ProgramRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(ProgramVoter::EDIT, $program);

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program = $form->getData();

            $repository->add($program);

            $this->addFlash('success', sprintf('Programa Actualizado'));

            return $this->redirectToRoute('app_program_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar el programa.');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView()
        ]);
    }
}
