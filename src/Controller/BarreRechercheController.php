<?php

namespace App\Controller;

use App\Form\BarreRechercheType;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BarreRechercheController extends AbstractController
{
    #[Route('/barre/recherche', name: 'barre_recherche')]
    public function search(Request $request): Response
    {

        $formBarre = $this->createForm(BarreRechercheType::class);
        $search = $formBarre->handleRequest($request);
        
        return $this->render('barre_recherche/barre.html.twig', [
            'formBarre' => $formBarre->createView()
        ]);
    }

    #[Route('/barre/index', name: 'recherche_resultat')]
    public function result() : Response
    {
        return $this->render('barre_recherche/index.html.twig');
    }

}
