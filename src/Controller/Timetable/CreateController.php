<?php

namespace App\Controller\Timetable;

use App\Entity\PersonInProgram;
use App\Entity\Timetable;
use App\Form\TimetableType;
use App\Repository\TimetableRepository;
use App\Security\Voter\TimetableVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateController extends AbstractController
{
    #[Route('/person/program/{id}/timetable/add', name: 'app_person_program_timetable_add')]
    public function add(
        PersonInProgram $personInProgram,
        TimetableRepository $repository,
        Request $request
    ): Response {
        $currentUser = $this->getUser();

        $timetable = new Timetable();
        $timetable->setCreatedBy($currentUser);

        $program = $personInProgram->getProgram();

        $this->denyAccessUnlessGranted(TimetableVoter::ADD, $timetable);

        $form = $this->createForm(TimetableType::class, $timetable, [
            'program' => $program
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timetable = $form->getData();
            $timetable->addPersonInProgram($personInProgram);

            $repository->add($timetable);

            $this->addFlash('success', 'Registro de tiempo añadido');

            return $this->redirectToRoute('app_person_program_timetable_list', [
                'id' => $personInProgram->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf("Hay errores en el formulario y no ha podido ser creado el registro de tiempo"));
        }

        return $this->render('timetable/add.html.twig', [
            'personInProgram' => $personInProgram,
            'timetable' => $timetable,
            'form' => $form->createView()
        ]);
    }
}
