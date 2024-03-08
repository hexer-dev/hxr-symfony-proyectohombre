<?php

namespace App\Controller\Document\Program;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewController extends AbstractController
{
    #[Route('/program/document/view/{id}', name: 'app_document_view_program')]
    public function view(
        Document $document
    ): Response
    {        
        $this->denyAccessUnlessGranted(DocumentVoter::VIEW, $document);
        
        return $this->render('document/program/view.html.twig', [
            'document' => $document,
        ]);
    }
}
