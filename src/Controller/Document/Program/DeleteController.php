<?php

namespace App\Controller\Document\Program;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/program/document/remove/{id}', name: 'app_program_document_delete')]
    public function remove(
        Document $document,
        DocumentRepository $repository
    ): Response {
        $program = $document->getProgram();

        $this->denyAccessUnlessGranted(ProgramVoter::DELETE, $program);

        if (null !== $document->getFile()) {
            $fileRemove = $document->getPath() . '/' . $document->getFile();

            if (file_exists($fileRemove) && is_file($fileRemove)) {
                unlink($fileRemove);
            }
        }

        $repository->remove($document);

        $this->addFlash('success', sprintf('Documento %s eliminado del programa %s', $document->getName(), $program->getName()));

        return $this->redirectToRoute('app_program_edit', [
            'id' => $program->getId()
        ]);
    }
}
