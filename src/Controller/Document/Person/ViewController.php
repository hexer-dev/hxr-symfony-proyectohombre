<?php

namespace App\Controller\Document\Person;

use App\Entity\Document;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewController extends AbstractController
{
    #[Route('/person/document/view/{id}', name: 'app_document_view_person')]
    public function view(
        Document $document
    ): Response
    {        
        $this->denyAccessUnlessGranted(DocumentVoter::VIEW, $document);
        
        return $this->render('document/person/view.html.twig', [
            'document' => $document,
        ]);
    }
}
