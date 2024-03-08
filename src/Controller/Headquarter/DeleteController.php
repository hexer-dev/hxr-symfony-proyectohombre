<?php

namespace App\Controller\Headquarter;

use App\Entity\Headquarter;
use App\Repository\HeadquarterRepository;
use App\Security\Voter\HeadquarterVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/headquarter/remove/{id}', name: 'app_headquarter_remove')]
    public function remove(
        Headquarter $headquarter,
        HeadquarterRepository $repository
    ): Response
    {
        $this->denyAccessUnlessGranted(HeadquarterVoter::DELETE, $headquarter);
        
        $repository->remove($headquarter);

        $this->addFlash('success', sprintf('Sede eliminada'));

        return $this->redirectToRoute('app_headquarter_list');
    }
}
