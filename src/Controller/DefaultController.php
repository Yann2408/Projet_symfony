<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

     /**
      * Undocumented function
      *
      * @param ProgramRepository $programRepository
      * @return Response
      *
      * @Route("/", name="accueil")
      */
    public function index(ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findTheLastSix();
       
        return $this->render('default/index.html.twig', ['website' => 'Wild Séries', 'program' => $program]);    
    }
}