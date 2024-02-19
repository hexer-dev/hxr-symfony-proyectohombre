<?php

namespace App\Controller\Headquarter;

use App\Entity\Headquarter;
use App\Repository\HeadquarterRepository;
use App\Security\Voter\HeadquarterVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListHeadquarterController extends AbstractController
{
    #[Route('/headquarter', name: 'app_headquarter_list')]
    public function list(
        HeadquarterRepository $repository,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted(HeadquarterVoter::LIST, new Headquarter);

        $headquarters = $repository->findAll();

        return $this->render('headquarter/index.html.twig', [
            'headquarters' => $headquarters,
        ]);
    }
}
