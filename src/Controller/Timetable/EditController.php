<?php

namespace App\Controller\Timetable;

use App\Entity\PersonInProgram;
use App\Entity\Program;
use App\Entity\Timetable;
use App\Form\TimetableType;
use App\Repository\TimetableRepository;
use App\Security\Voter\TimetableVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    #[Route('/person/program/{personInProgram}/timetable/edit/{id}', name: 'app_person_program_timetable_edit')]
    public function edit(
        Timetable $timetable,
        PersonInProgram $personInProgram,
        TimetableRepository $repository,
        Request $request
    ): Response
    {
        $currentUser = $this->getUser();

        $this->denyAccessUnlessGranted(TimetableVoter::EDIT, $timetable);

        $program = $personInProgram->getProgram();

        $form = $this->createForm(TimetableType::class, $timetable, [
            'program' => $program
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timetable = $form->getData();
            $timetable->setUpdatedBy($currentUser);

            $repository->add($timetable);

            $this->addFlash('success', 'Registro de tiempo finalizado');

            return $this->redirectToRoute('app_person_program_timetable_list', [
                'id' => $personInProgram->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf("Hay errores en el formulario y no ha podido ser finalizado el registro de tiempo"));
        }

        return $this->render('timetable/edit.html.twig', [
            'personInProgram' => $personInProgram,
            'timetable' => $timetable,
            'form' => $form->createView()
        ]);
    }
}
