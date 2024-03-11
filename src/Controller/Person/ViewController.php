<?php

namespace App\Controller\Person;

use App\Entity\Person;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewController extends AbstractController
{
    #[Route('/person/view/{id}', name: 'app_person_view')]
    public function view(
        Person $person,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::VIEW, $person);

        return $this->render('person/view.html.twig', [
            'person' => $person,
        ]);
    }
}
