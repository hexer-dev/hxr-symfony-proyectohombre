<?php

namespace App\Controller\Person;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/person', name: 'app_person_list')]
    public function list(
        PersonRepository $repository,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST, new Person);

        $currentUser = $this->getUser();
        $headquarter = $currentUser->getHeadquarter();       

        if (null !== $headquarter) {
            $people = $repository->findBy(['headquarter' => $headquarter]);
        } else {
            $people = $repository->findAll();
        }           

        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }
}
