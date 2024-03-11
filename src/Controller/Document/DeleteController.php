<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/document/remove/{id}', name: 'app_document_remove')]
    public function remove(
        Document $document,
        DocumentRepository $repository
    ): Response
    {
        $this->denyAccessUnlessGranted(DocumentVoter::DELETE, $document);

        if (null !== $document->getFile()) {
            $fileRemove = $document->getPath() . '/' . $document->getFile();

            if (file_exists($fileRemove) && is_file($fileRemove)) {
                unlink($fileRemove);
            }
        }

        $repository->remove($document);

        $this->addFlash('success', sprintf('Documento eliminado'));

        return $this->redirectToRoute('app_document_list');
    }
}
