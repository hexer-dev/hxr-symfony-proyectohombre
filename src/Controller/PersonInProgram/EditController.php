<?php

namespace App\Controller\PersonInProgram;

use App\Entity\PersonInProgram;
use App\Form\PersonInProgramType;
use App\Repository\PersonInProgramRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    #[Route('/person/program/edit/{id}', name: 'app_person_program_edit')]
    public function edit(
        PersonInProgram $personInProgram,
        PersonInProgramRepository $repository,
        Request $request
    ): Response {
        $urlReferer = $request->server->get('HTTP_REFERER');
        $person = $personInProgram->getPerson();
        $program = $personInProgram->getProgram();

        $this->denyAccessUnlessGranted(PersonVoter::DELETE, $person);

        $form = $this->createForm(PersonInProgramType::class, $personInProgram, [
            'urlReturn' => $urlReferer
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personInProgram = $form->getData();

            $urlReferer = $form->get('urlReturn')->getData();

            try {
                $repository->add($personInProgram);
            } catch (\RuntimeException $e) {
                throw new \RuntimeException("Error al intentar insertar en base de datos");
            }

            $this->addFlash('success', sprintf('Información Actualizada'));

            return $this->redirect($urlReferer);
        } else if ($form->isSubmitted()) {
            $urlReferer = $form->get('urlReturn')->getData();

            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar la información.');

            return $this->redirect($urlReferer);
        }

        return $this->render('personInProgram/edit.html.twig', [
            'urlReturn' => $urlReferer,
            'namePerson' => $person->__toString(),
            'titleProgram' => $program->getName(),
            'form' => $form->createView()
        ]);
    }
}
