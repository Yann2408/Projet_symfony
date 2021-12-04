<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends AbstractController
{

    /**
     * Undocumented function
     *
     * @return Response
     * @Route("/category/", name="category_index")
     */
    public function index(CategoryRepository $CategoryRepository): Response
    {
        $categories = $CategoryRepository->findAll();
    
             return $this->render('category/index.html.twig', ['categories' => $categories]);
    }


     /**
     * @Route("category/new", name="new_category")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', ["form" => $form->createView(),]);
    }

    /**
     * Undocumented function
     *
     * @param string $categoryName
     * @return Response
     * 
     * @Route("/category/{categoryName}", name="category_show")
     */
        public function show(string $categoryName, ProgramRepository $programRepository): Response
        {
            $programs = $programRepository->findProgramInCategory($categoryName);

            if (!$categoryName) {
                throw $this->createNotFoundException(
                'Nom invalide ou catégorie vide');
            }

            return $this->render('category/show.html.twig', ['programs' => $programs, 'categoryName' =>$categoryName]);
        } 
}