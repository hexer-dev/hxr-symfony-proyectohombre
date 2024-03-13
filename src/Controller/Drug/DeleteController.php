<?php

namespace App\Controller\Drug;

use App\Entity\Drug;
use App\Repository\DrugRepository;
use App\Security\Voter\DrugVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/drug/remove/{id}', name: 'app_drug_remove')]
    public function remove(
        Drug $drug,
        DrugRepository $repository
    ): Response
    {
        $this->denyAccessUnlessGranted(DrugVoter::DELETE, $drug);

        $repository->remove($drug);

        return $this->redirectToRoute('app_drug_list');
    }
}
