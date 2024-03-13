<?php

namespace App\Controller\Drug;

use App\Entity\Drug;
use App\Form\DrugType;
use App\Repository\DrugRepository;
use App\Security\Voter\DrugVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    #[Route('/drug/edit/{id}', name: 'app_drug_edit')]
    public function edit(
        Drug $drug,
        DrugRepository $repository,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted(DrugVoter::EDIT, $drug);

        $form = $this->createForm(DrugType::class, $drug);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drug = $form->getData();

            $repository->add($drug);

            $this->addFlash('success', 'Adicción editada correctamente');

            return $this->redirectToRoute('app_drug_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf("Hay errores en el formulario y no ha podido ser editado el tipo de adicción"));
        }

        return $this->render('drug/edit.html.twig', [
            'drug' => $drug,
            'form' => $form->createView()
        ]);
    }
}
