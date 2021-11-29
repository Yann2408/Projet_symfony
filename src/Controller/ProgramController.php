<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgramController extends AbstractController
{
  /**
   * Undocumented function
   *
   * @return Response
   * 
   * @Route("/program/", name="program_index")
   */
  
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Séries',]);
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return Response
     * 
     * @Route("/program/{id<\d+>}", methods={"GET"}, name="program_show")
     */

    public function show(int $id): Response
    {
        return $this->render('program/show.html.twig', ['id' => $id]);
    }
}

/**

     * @Route("/program/", name="program_index")

     */

