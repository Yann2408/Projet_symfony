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
    public function search(programRepository $progrmaRepository, Request $request): Response
    {

        $programs = $progrmaRepository->findAll();

        $formBarre = $this->createForm(BarreRechercheType::class);
        $search = $formBarre->handleRequest($request);

        if ($formBarre->isSubmitted() && $formBarre->isValid()){
            $programs = $progrmaRepository->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData()
            );
        }
        
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
