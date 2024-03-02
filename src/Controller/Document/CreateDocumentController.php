<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateDocumentController extends AbstractController
{
    #[Route('/document/add', name: 'app_document_add')]
    public function add(
        SluggerInterface $slugger,
        DocumentRepository $repository,
        Request $request
    ): Response {
        $document = new Document();

        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();

            /*subida archivo*/
            $fileUpload = $form->get('file')->getData();

            if ($fileUpload) {
                $originalFilename = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileUpload->guessExtension();

                try {
                    $fileUpload->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \RuntimeException("Error al subir el archivo");
                }

                $document->setFile($newFilename);
            }

            $repository->add($document);

            $this->addFlash('sucess', sprintf('Documento creado correctamente'));

            return $this->redirectToRoute('app_document_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear el documento.');
        }

        return $this->render('document/add.html.twig', [
            'document' => $document,
            'form' => $form->createView()
        ]);
    }
}
