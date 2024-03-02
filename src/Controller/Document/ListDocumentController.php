<?php

namespace App\Controller\Document;

use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListDocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document_list')]
    public function list(
        DocumentRepository $repository,
        Request $request
    ): Response
    {
        $documents = $repository->findAll();

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
        ]);
    }
}
