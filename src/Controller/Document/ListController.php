<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/document', name: 'app_document_list')]
    public function list(
        DocumentRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(DocumentVoter::LIST, new Document);        

        $currentUser = $this->getUser();
        $headquarter = $currentUser->getHeadquarter();

        if (null !== $headquarter) {
            $headquarter = $headquarter->getId();

            $documents = $repository->findBy(['headquarter' => $headquarter]);
        } else {
            $documents = $repository->findAll();
        }

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
        ]);
    }
}
