<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Program;
use App\Repository\SeasonRepository;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**

     * @Route("/", name="accueil")

     */

    public function index(ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {

        $program = $programRepository->findTheLastSix();
        // $season = $seasonRepository->findAll();
        // $nbSeasons = $seasonRepository->findNumberOfSeason();

        return $this->render('default/index.html.twig', ['website' => 'Wild Séries', 'program' => $program ]);    
    }

    // public function index(): Response
    // {
    //     return $this->render('default/index.html.twig', [
    //         'website' => 'Wild Séries',     
    //      ]);
    // }
}