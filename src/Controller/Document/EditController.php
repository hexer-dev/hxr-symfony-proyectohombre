<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Security\Voter\DocumentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EditController extends AbstractController
{
    #[Route('/document/edit/{id}', name: 'app_document_edit')]
    public function edit(
        Document $document,
        DocumentRepository $repository,
        SluggerInterface $slugger,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(DocumentVoter::EDIT, $document);

        $currentUser = $this->getUser();

        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();
            $headquarter = $currentUser->getHeadquarter();

            $fileUpload = $form->get('file')->getData();
            $typeDocument = $form->get('typeDocument')->getData();

            if ($typeDocument == "file") {
                if ($fileUpload) {
                    $pathUpload = $this->getParameter('files_directory');

                    if (null === $headquarter) {
                        $pathUpload .= 'user/' .  $currentUser->getId();
                    } else {
                        $pathUpload .= 'headquarter/' . $headquarter->getId();
                    }

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
                        $fileRemove = $document->getPath() . $document->getFile();

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
                    } else {
                        dump("No existe");
                    }
                }
                $document->setFile(null);
                $document->setPath(null);
            }

            $repository->add($document);

            $this->addFlash('success', sprintf('Documento Actualizada'));

            return $this->redirectToRoute('app_document_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar el documento.');
        }

        return $this->render('document/edit.html.twig', [
            'entity' => $document,
            'form' => $form->createView()
        ]);
    }
}
