<?php

namespace App\Controller\PersonInProgram;

use App\Entity\PersonInProgram;
use App\Entity\Program;
use App\Repository\PersonInProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateFromProgramController extends AbstractController
{
    #[Route('/program/person/add/{id}', name: 'app_program_person_add')]
    public function create(
        Program $program,
        PersonInProgramRepository $respository,
        Request $request
    ): Response
    {
        return $this->render('create_from_program/index.html.twig', [
            'controller_name' => 'CreateFromProgramController',
        ]);
    }
}
