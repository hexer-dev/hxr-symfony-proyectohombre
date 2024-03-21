<?php

namespace App\Controller\Report;

use App\Filter\ReportFiler;
use App\Form\Filter\ReportFilterType;
use App\Repository\PersonInProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GenerateController extends AbstractController
{
    #[Route('/report/admin', name: 'app_report_admin')]
    public function report(
        PersonInProgramRepository $repository,
        Request $request
    ): Response {
        $reportFilter = new ReportFiler();

        $form = $this->createForm(ReportFilterType::class, $reportFilter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();

            $dataPersonProgram = $repository->getDataByFilter($filter);
            
            return $this->render('report/result.html.twig', [
                'personinprogram' => $dataPersonProgram
            ]);
        } else if ($form->isSubmitted()) {
            $this->addFlash('danger', 'Los campos fechas no son válidos');
        }

        return $this->render('report/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
