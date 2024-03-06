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

class CreateController extends AbstractController
{
    #[Route('/document/add', name: 'app_document_add')]
    public function add(
        SluggerInterface $slugger,
        DocumentRepository $repository,
        Request $request
    ): Response {
        $currentUser = $this->getUser();

        $document = new Document();
        $document->setCreatedBy($currentUser);

        $this->denyAccessUnlessGranted(DocumentVoter::ADD, $document);        

        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();

            $headquarter = $currentUser->getHeadquarter();

            if (null !== $headquarter) {
                $document->setHeadquarter($headquarter);
            }

            $typeDocument = $form->get('typeDocument')->getData();

            if ($typeDocument == "file") {
                $pathUpload = $this->getParameter('files_directory');

                if (null === $headquarter) {
                    $pathUpload .= 'user/' .  $currentUser->getId();
                } else {
                    $pathUpload .= 'headquarter/' . $headquarter->getId();
                }

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

            $this->addFlash('success', sprintf('Documento creado correctamente %s', $document->getName()));

            return $this->redirectToRoute('app_document_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear el documento.');
        }

        return $this->render('document/add.html.twig', [
            'entity' => $document,
            'form' => $form->createView()
        ]);
    }
}
