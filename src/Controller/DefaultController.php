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
        // dd(count($program[5]->getSeason()));
        // var_dump($program[5]->getSeason());
        // dd($program[5]->getSeason([0]));
       

        return $this->render('default/index.html.twig', ['website' => 'Wild Séries', 'program' => $program ]);    
    }

    // public function index(): Response
    // {
    //     return $this->render('default/index.html.twig', [
    //         'website' => 'Wild Séries',     
    //      ]);
    // }
}