<?php

namespace App\Controller\PersonInProgram;

use App\Entity\Person;
use App\Entity\PersonInProgram;
use App\Form\PersonInProgramType;
use App\Repository\PersonInProgramRepository;
use App\Security\Voter\PersonVoter;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateFromPersonController extends AbstractController
{
    #[Route('/person/program/add/{id}', name: 'app_person_program_add')]
    public function addPerson(
        Person $person,
        PersonInProgramRepository $respository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT, $person);

        $personInProgram = new PersonInProgram();
        $personInProgram->setPerson($person);

        $headquarter = $person->getHeadquarter();

        $form = $this->createForm(PersonInProgramType::class, $personInProgram, [
            'headquarter' => $headquarter
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personInProgram = $form->getData();

            $program = $personInProgram->getProgram();

            $listPersonInProgram = $respository->findByPersonAndProgram($person, $program);

            $existPersonInProgram = (null === $listPersonInProgram || count($listPersonInProgram) == 0) ? false : true;

            if ($existPersonInProgram) {
                $this->addFlash('danger', sprintf('Ya existe la persona beneficiaria %s asociada a ese programa: %s', $person->__toString(), $program->getName()));

                return $this->redirectToRoute('app_person_view', [
                    'id' => $person->getId()
                ]);
            }

            try {
                $respository->add($personInProgram);
            } catch (RuntimeException $e) {
                throw new \RuntimeException("Error al intentar insertar en base de datos");
            }

            $this->addFlash('success', sprintf('Persona asociada al programa correctamente'));

            return $this->redirectToRoute('app_person_view', [
                'id' => $person->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf("Hay errores en el formulario y no ha sido posible asociar la persona al programa"));
        }

        return $this->render('personInProgram/add.html.twig', [
            'person' => $person,
            'form' => $form->createView()
        ]);
    }
}
