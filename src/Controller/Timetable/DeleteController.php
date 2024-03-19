<?php

namespace App\Controller\Timetable;

use App\Entity\Timetable;
use App\Repository\TimetableRepository;
use App\Security\Voter\TimetableVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/person/program/timetable/remove/{id}', name: 'app_person_program_timetable_remove')]
    public function remove(
        Timetable $timetable,
        TimetableRepository $repository
    ): Response
    {
        $this->denyAccessUnlessGranted(TimetableVoter::DELETE, $timetable);

        $personInProgram = $timetable->getPersonInProgram()[0];

        $repository->remove($timetable);

        $this->addFlash("sucess", "Registro de tiempo eliminado");

        return $this->redirectToRoute('app_person_program_timetable_list', [
            'id' => $personInProgram->getId()
        ]);
    }
}
