<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function dashboard(
        ProgramRepository $repository,
        Request $request
    ): Response
    {
        $currentUser = $this->getUser();
        $headquarter = $currentUser->getHeadquarter();        

        if (null !== $headquarter) {
            $programsActived = $repository->managerPrograms($headquarter);
            $programsFinished = $repository->managerProgramsFinished($headquarter);
        } else {
            $programsActived = $repository->allProgramsActived();
            $programsFinished = $repository->allProgramsFinished();
        }        

        return $this->render('dashboard.html.twig', [
            'numberProgramsActived' => (null !== $programsActived) ? count($programsActived) : 0,
            'numberProgramsFinished' => (null !== $programsFinished) ? count($programsFinished) : 0,
        ]);
    }
}
