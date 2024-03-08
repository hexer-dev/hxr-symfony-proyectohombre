<?php

namespace App\Controller\Program;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/program', name: 'app_program_list')]
    public function list(
        ProgramRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(ProgramVoter::LIST, new Program);

        $currentUser = $this->getUser();
        $headquarter = $currentUser->getHeadquarter();        

        if (null !== $headquarter) {
            $programs = $repository->managerPrograms($headquarter);
        } else {
            $programs = $repository->findAll();
        }        

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }
}
