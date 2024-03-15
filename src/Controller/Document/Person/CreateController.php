<?php

namespace App\Controller\Document\Person;

use App\Entity\Document;
use App\Entity\Person;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateController extends AbstractController
{
    #[Route('/person/document/add/{id}', name: 'app_person_document_add')]
    public function add(
        Person $person,
        DocumentRepository $repository,
        SluggerInterface $slugger,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT, $person);

        $currentUser = $this->getUser();

        $document = new Document();
        $document->setCreatedBy($currentUser);
        $document->setPerson($person);

        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();

            $headquarter = $person->getHeadquarter();
            $document->setHeadquarter($headquarter);

            $pathUpload = $this->getParameter('files_directory') . 'person/' . $person->getId();

            $typeDocument = $form->get('typeDocument')->getData();

            if ($typeDocument == "file") {
                $fileUpload = $form->get('file')->getData();

                if ($fileUpload) {
                    $originalFilename = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileUpload->guessExtension();

                    try {
                        $fileUpload->move(
                            $pathUpload,
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new \RuntimeException("Error al subir el archivo");
                    }

                    $document->setFile($newFilename);
                    $document->setPath($pathUpload);
                    $document->setLink(null);
                }
            } else {
                $document->setFile(null);
                $document->setPath(null);
            }

            $repository->add($document);

            $this->addFlash('success', sprintf('Documento creado correctamente %s asociado a la persona %s %s', $document->getName(), $person->getName(), $person->getLastname()));

            return $this->redirectToRoute('app_person_edit', [
                'id' => $person->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf('Hay errores en el formulario y no ha sido posible crear el documento asociado a la persona %s.', $person->__toString()));
        }

        return $this->render('document/add.html.twig', [
            'entity' => $document,
            'person' => $person,
            'form' => $form->createView()
        ]);
    }
}
