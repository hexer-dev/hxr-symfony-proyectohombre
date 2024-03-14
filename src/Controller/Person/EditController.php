<?php

namespace App\Controller\Person;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    #[Route('/person/edit/{id}', name: 'app_person_edit')]
    public function edit(
        Person $person,
        PersonRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT, $person);

        $personDataBBDD = $repository->find($person->getId());
        $currentNif = $personDataBBDD->getNif();

        $form = $this->createForm(PersonType::class, $person, [
            'headquarter' => $person->getHeadquarter()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $person = $form->getData();
            $headquarter = $person->getHeadquarter();

            $characterReplace = array(' ', '-', '.');

            $nif = str_replace($characterReplace, '', $person->getNif());
            
            $nifHasChanged = ($currentNif != $nif) ? true : false;

            if ($nifHasChanged) {
                $listPerson = $repository->findByNifAndHeadquarter($nif, $headquarter);

                $existPerson = (null === $listPerson || count($listPerson) == 0) ? false : true;
    
                if ($existPerson) {
                    $this->addFlash('danger', sprintf('Ya existe una persona beneficiaria con NIF: %s. Los cambios en la persona no se han realizado', $nif));
    
                    return $this->redirectToRoute('app_person_list');
                }
            }            

            $phone = null;
            if (
                null !== $person->getPhone()
                && !empty($person->getPhone())
            ) {
                $phone = str_replace($characterReplace, '', $person->getPhone());
            }

            $contactPhone = null;
            if (
                null !== $person->getContactPhone()
                && !empty($person->getContactPhone())
            ) {
                $contactPhone = str_replace($characterReplace, '', $person->getContactPhone());
            }

            $person->setNif($nif);
            $person->setPhone($phone);
            $person->setContactPhone($contactPhone);

            try {
                $repository->add($person);
            } catch (\RuntimeException $e) {
                throw new \RuntimeException("Error al intentar insertar en base de datos");
            }

            $this->addFlash('success', sprintf('Persona Beneficiaria creada'));

            return $this->redirectToRoute('app_person_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear la persona beneficiaria');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView()
        ]);
    }
}
