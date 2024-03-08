<?php

namespace App\Controller\Program;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/program/remove/{id}', name: 'app_program_delete')]
    public function remove(
        Program $program,
        ProgramRepository $repository,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted(ProgramVoter::DELETE, $program);

        /* Eliminar todos los archivos del programa */        

        $repository->remove($program);

        $this->addFlash('success', sprintf('Programa eliminado'));

        return $this->redirectToRoute('app_program_list');
    }
}
