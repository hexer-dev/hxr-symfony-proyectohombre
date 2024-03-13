<?php

namespace App\Controller\Drug;

use App\Entity\Drug;
use App\Repository\DrugRepository;
use App\Security\Voter\DrugVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/drug', name: 'app_drug_list')]
    public function list(
        DrugRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(DrugVoter::LIST, new Drug);

        $drugs = $repository->findAll();

        return $this->render('drug/index.html.twig', [
            'drugs' => $drugs,
        ]);
    }
}
