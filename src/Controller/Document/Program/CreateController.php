<?php

namespace App\Controller\Document\Program;

use App\Entity\Document;
use App\Entity\Program;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateController extends AbstractController
{
    #[Route('/program/document/add/{id}', name: 'app_program_document_add')]
    public function add(
        Program $program,
        SluggerInterface $slugger,
        DocumentRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(ProgramVoter::EDIT, $program);

        $currentUser = $this->getUser();

        $document = new Document();
        $document->setCreatedBy($currentUser);

        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();

            $headquarter = $program->getHeadquarter();

            $document->setHeadquarter($headquarter);
            $document->setProgram($program);

            $pathUpload = $this->getParameter('files_directory') . 'program/' . $program->getId();

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

            $this->addFlash('success', sprintf('Documento creado correctamente %s asociado al programa %s', $document->getName(), $program->getName()));

            return $this->redirectToRoute('app_program_edit', [
                'id' => $program->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear el documento asociado al programa.');
        }

        return $this->render('document/add.html.twig', [
            'entity' => $document,
            'program' => $program, 
            'form' => $form->createView()
        ]);
    }
}
