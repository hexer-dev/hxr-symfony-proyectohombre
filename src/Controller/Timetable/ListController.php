<?php

namespace App\Controller\Timetable;

use App\Entity\PersonInProgram;
use App\Entity\Timetable;
use App\Repository\TimetableRepository;
use App\Security\Voter\TimetableVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/person/program/timetable/{id}', name: 'app_person_program_timetable_list')]
    public function list(
        PersonInProgram $personInProgram,
        TimetableRepository $repository,
        Request $request
    ): Response
    {
        $currentUser = $this->getUser();

        $timetable = new Timetable();
        $timetable->setCreatedBy($currentUser);

        $this->denyAccessUnlessGranted(TimetableVoter::LIST, $timetable);

        return $this->render('timetable/index.html.twig', [
            'personInProgram' => $personInProgram,
        ]);
    }
}
