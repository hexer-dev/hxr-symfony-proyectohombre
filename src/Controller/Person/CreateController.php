<?php

namespace App\Controller\Person;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use RuntimeException;
use SebastianBergmann\Environment\Runtime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateController extends AbstractController
{
    #[Route('/person/add', name: 'app_person_add')]
    public function add(
        PersonRepository $repository,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        $person = new Person();

        $this->denyAccessUnlessGranted(PersonVoter::ADD, $person);

        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();

            $headquarter = $currentUser->getHeadquarter();

            $characterReplace = array(' ', '-', '.');

            $nif = str_replace($characterReplace, '', $person->getNif());

            $listPerson = $repository->findByNifAndHeadquarter($nif, $headquarter);

            $existPerson = (null === $listPerson || count($listPerson) == 0) ? false : true;

            if ($existPerson) {
                $this->addFlash('danger', sprintf('Ya existe una persona beneficiaria con NIF: %s', $nif));

                return $this->redirectToRoute('app_person_list');
            }

            $phone = null;
            if (
                null !== $person->getPhone()
                && !empty($person->getPhone())
            ) {
                $phone = str_replace($characterReplace, '', $person->getPhone());
            }

            $person->setHeadquarter($headquarter);
            $person->setNif($nif);
            $person->setPhone($phone);

            try {
                $repository->add($person);
            } catch (RuntimeException $e) {
                throw new \RuntimeException("Error al intentar insertar en base de datos");
            }

            $this->addFlash('success', sprintf('Persona Beneficiaria creada'));

            return $this->redirectToRoute('app_person_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear la persona beneficiaria');
        }

        return $this->render('person/add.html.twig', [
            'person' => $person,
            'form' => $form->createView()
        ]);
    }
}
