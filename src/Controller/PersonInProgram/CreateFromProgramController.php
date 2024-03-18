<?php

namespace App\Controller\PersonInProgram;

use App\Entity\Person;
use App\Entity\PersonInProgram;
use App\Entity\Program;
use App\Form\PersonInProgramType;
use App\Repository\PersonInProgramRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateFromProgramController extends AbstractController
{
    #[Route('/program/person/add/{id}', name: 'app_program_person_add')]
    public function createProgram(
        Program $program,
        PersonInProgramRepository $respository,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        $this->denyAccessUnlessGranted(PersonVoter::ADD, new Person);

        $personInProgram = new PersonInProgram();
        $personInProgram->setProgram($program);

        $headquarter = $currentUser->getHeadquarter();

        $form = $this->createForm(PersonInProgramType::class, $personInProgram, [
            'headquarter' => $headquarter
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personInProgram = $form->getData();

            $person = $personInProgram->getPerson();

            $listPersonInProgram = $respository->findByPersonAndProgram($person, $program);

            $existPersonInProgram = (null === $listPersonInProgram || count($listPersonInProgram) == 0) ? false : true;

            if ($existPersonInProgram) {
                $this->addFlash('danger', sprintf('Ya existe la persona beneficiaria %s asociada a ese programa: %s', $person->__toString(), $program->getName()));

                return $this->redirectToRoute('app_program_view', [
                    'id' => $program->getId()
                ]);
            }

            try {
                $respository->add($personInProgram);
            } catch (\RuntimeException $e) {
                throw new \RuntimeException("Error al intentar insertar en base de datos");
            }

            $this->addFlash('success', sprintf('Persona asociada al programa correctamente'));

            return $this->redirectToRoute('app_program_view', [
                'id' => $program->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf("Hay errores en el formulario y no ha sido posible asociar la persona al programa"));
        }

        return $this->render('personInProgram/add.html.twig', [
            'program' => $program,
            'form' => $form->createView()
        ]);
    }
}
