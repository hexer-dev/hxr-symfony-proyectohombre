<?php

namespace App\Controller\Program;

use App\Entity\Program;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewController extends AbstractController
{
    #[Route('/program/view/{id}', name: 'app_program_view')]
    public function view(
        Program $program,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted(ProgramVoter::VIEW, $program);

        return $this->render('program/view.html.twig', [
            'program' => $program,
        ]);
    }
}
