<?php

namespace App\Controller\Person;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/person/remove/{id}', name: 'app_person_remove')]
    public function remove(
        Person $person,
        PersonRepository $repository,
        Request $request
    ): Response
    {   
        $this->denyAccessUnlessGranted(PersonVoter::DELETE, $person);

        $repository->remove($person);

        $this->addFlash('success', sprintf('Persona Beneficiaria elimnada'));

        return $this->redirectToRoute('app_person_list');
    }
}
