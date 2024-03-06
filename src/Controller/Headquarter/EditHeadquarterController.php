<?php

namespace App\Controller\Headquarter;

use App\Entity\Headquarter;
use App\Form\HeadquarterType;
use App\Repository\HeadquarterRepository;
use App\Security\Voter\HeadquarterVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditHeadquarterController extends AbstractController
{
    #[Route('/headquarter/edit/{id}', name: 'app_headquarter_edit')]
    public function edit(
        Headquarter $headquarter,
        HeadquarterRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(HeadquarterVoter::EDIT, $headquarter);

        $form = $this->createForm(HeadquarterType::class, $headquarter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $headquarter = $form->getData();

            $repository->add($headquarter);

            $this->addFlash('success', sprintf('Sede Actualizada'));

            return $this->redirectToRoute('app_headquarter_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible actualizar la sede.');
        }

        return $this->render('headquarter/edit.html.twig', [
            'headquarter' => $headquarter,
            'form' => $form->createView()
        ]);
    }
}
