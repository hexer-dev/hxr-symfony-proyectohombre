<?php

namespace App\Controller\Document\Program;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Security\Voter\ProgramVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EditController extends AbstractController
{
    #[Route('/program/document/edit/{id}', name: 'app_program_document_edit')]
    public function edit(
        Document $document,
        DocumentRepository $repository,
        SluggerInterface $slugger,
        Request $request
    ): Response {
        $program = $document->getProgram();

        $this->denyAccessUnlessGranted(ProgramVoter::EDIT, $program);

        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();

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

                    /*eliminamos el antiguo*/
                    if (null !== $document->getFile()) {
                        $fileRemove = $document->getPath() . '/' . $document->getFile();

                        if (file_exists($fileRemove) && is_file($fileRemove)) {
                            unlink($fileRemove);
                        }
                    }

                    $document->setFile($newFilename);
                    $document->setPath($pathUpload);
                    $document->setLink(null);
                }
            } else {
                if (null !== $document->getFile()) {
                    $fileRemove = $document->getPath() . '/' . $document->getFile();

                    if (file_exists($fileRemove) && is_file($fileRemove)) {
                        unlink($fileRemove);
                    }
                }
                $document->setFile(null);
                $document->setPath(null);
            }

            $repository->add($document);

            $this->addFlash('success', sprintf('Documento editado correctamente %s asociado al programa %s', $document->getName(), $program->getName()));

            return $this->redirectToRoute('app_program_edit', [
                'id' => $program->getId()
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar el documento.');
        }

        return $this->render('document/program/edit.html.twig', [
            'entity' => $document,
            'form' => $form->createView()
        ]);
    }
}
