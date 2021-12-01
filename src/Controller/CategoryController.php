<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
        // $categories = $this->getDoctrine()
        //      ->getRepository(Category::class)
        //      ->findAll();

             
             return $this->render('category/index.html.twig', ['categories' => $categories]);
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
        $programs = $programRepository->findProgramInHorreur($categoryName);

         if (!$programs) {
            throw $this->createNotFoundException(
               'No category with name : '.$categoryName.' found in category\'s table.');
         }

        return $this->render('category/show.html.twig', ['programs' => $programs, 'categoryName' =>$categoryName]);
    }
    
}