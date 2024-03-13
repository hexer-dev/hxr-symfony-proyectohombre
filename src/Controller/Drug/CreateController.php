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

class CreateController extends AbstractController
{
    #[Route('/drug/add', name: 'app_drug_add')]
    public function add(
        DrugRepository $repository,
        Request $request
    ): Response {
        $drug = new Drug();

        $this->denyAccessUnlessGranted(DrugVoter::ADD, $drug);

        $form = $this->createForm(DrugType::class, $drug);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drug = $form->getData();

            $repository->add($drug);

            $this->addFlash('success', 'Adicción creada correctamente');

            return $this->redirectToRoute('app_drug_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', sprintf("Hay errores en el formulario y no ha podido ser creada el tipo de adicción"));
        }

        return $this->render('drug/add.html.twig', [
            'drug' => $drug,
            'form' => $form->createView()
        ]);
    }
}
