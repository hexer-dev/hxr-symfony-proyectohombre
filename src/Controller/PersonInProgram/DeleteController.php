<?php

namespace App\Controller\PersonInProgram;

use App\Entity\PersonInProgram;
use App\Repository\PersonInProgramRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/person/program/remove/{id}', name: 'app_person_program_remove')]
    public function remove(
        PersonInProgram $personInProgram,
        PersonInProgramRepository $respository,
        Request $request
    ): Response {
        $urlReferer = $request->server->get('HTTP_REFERER');

        $program = $personInProgram->getProgram();

        $person = $personInProgram->getPerson();

        $this->denyAccessUnlessGranted(PersonVoter::DELETE, $person);

        $respository->remove($personInProgram);

        $this->addFlash('success', sprintf("Se ha eliminado correctamen la pesona %s asociada al program %s", $person->__toString(), $program->getName()));

        return $this->redirect($urlReferer);
    }
}
