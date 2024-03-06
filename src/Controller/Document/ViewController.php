<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewController extends AbstractController
{
    #[Route('/document/view/{id}', name: 'app_document_view')]
    public function index(
        Document $document
    ): Response
    {        
        $this->denyAccessUnlessGranted(DocumentVoter::VIEW, $document);
        
        return $this->render('document/view.html.twig', [
            'document' => $document,
        ]);
    }
}
