<?php

namespace App\Controller\Document\Person;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/person/document/remove/{id}', name: 'app_person_document_remove')]
    public function remove(
        Document $document,
        DocumentRepository $repository,
        Request $request
    ): Response
    {
        $person = $document->getPerson();

        $this->denyAccessUnlessGranted(PersonVoter::EDIT, $person);

        if (null !== $document->getFile()) {
            $fileRemove = $document->getPath() . '/' . $document->getFile();

            if (file_exists($fileRemove) && is_file($fileRemove)) {
                unlink($fileRemove);
            }
        }

        $repository->remove($document);

        $this->addFlash('success', sprintf('Documento %s eliminado asociado a la persona %s %s', $document->getName(), $person->getName(), $person->getLastname()));

        return $this->redirectToRoute('app_person_edit', [
            'id' => $person->getId()
        ]);
    }
}
