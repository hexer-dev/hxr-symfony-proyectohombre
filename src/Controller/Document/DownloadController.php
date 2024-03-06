<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DownloadController extends AbstractController
{
    #[Route('/document/download/{id}', name: 'app_document_download')]
    public function download(
        Document $document
    ): Response {
        $this->denyAccessUnlessGranted(DocumentVoter::DOWNLOAD, $document);        

        $file = $document->getPath() . '/' . $document->getFile();

        if (!file_exists($file)) {
            throw new \RuntimeException('No existe el archivo a descargar');
        }

        $response = new BinaryFileResponse($file);

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/octect-stream; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $document->getFile() . '"');

        return $response;
    }
}
