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

class CreateHeadquarterController extends AbstractController
{
    #[Route('/headquarter/add', name: 'app_headquarter_add')]
    public function add(
        HeadquarterRepository $repository,
        Request $request
    ): Response {
        $headquarter = new Headquarter();

        $this->denyAccessUnlessGranted(HeadquarterVoter::ADD, $headquarter);

        $form = $this->createForm(HeadquarterType::class, $headquarter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $headquarter = $form->getData();

            $repository->add($headquarter);

            $this->addFlash('sucess', sprintf('Sede creada correctamente'));

            return $this->redirectToRoute('app_headquarter_list');
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Hay errores en el formulario y no ha sido posible crear la sede.');
        }

        return $this->render('headquarter/add.html.twig', [
            'headquarter' => $headquarter,
            'form' => $form->createView()
        ]);
    }
}
