<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Form\BarreRechercheType;
use Symfony\Component\Mime\Email;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProgramController extends AbstractController
{
  /**
   * Undocumented function
   *
   * @return Response
   * 
   * @Route("/program/", name="program_index")
   */
    public function index(programRepository $progrmaRepository, Request $request): Response
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

        return $this->render('program/index.html.twig', ['programs' => $programs, 
            'formBarre' => $formBarre->createView()
        ]);
    }

     /**
     * @Route("program/new", name="new_program")
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());

            $entityManager->persist($program);

            $entityManager->flush();

            $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to('yannmalfer@gmail.com')
                    ->subject('une nouvelle série vient d\'être publiée')
                    ->html($this->renderView('Program/newProgramEmail.html.twig' , ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('accueil');
        }

        return $this->render('program/new.html.twig', ["form" => $form->createView(),]);
    }
    
   /**
    * Undocumented function
    *
    * @param Program $program_slug
    * @param SeasonRepository $seasonRepository
    * @return Response
    *@Route("/program/{slug}", methods={"GET"}, name="program_show")
    */
    public function show(Program $program_slug, SeasonRepository $seasonRepository ): Response
    {
        $seasons = $seasonRepository->findSeasonInProgram($program_slug);

         if (!$program_slug) {
            throw $this->createNotFoundException(
               'Nom invalide ou catégorie vide');
         }
         return $this->render('program/show.html.twig', ['seasons' => $seasons, 'program' => $program_slug]);
    }


    /**
     * Undocumented function
     *
     * @param integer $programId
     * @param integer $seasonId
     * @return void
     * 
     * @Route("/program/{programId}/seasons/{seasonId}", methods={"GET"}, name="program_season_show" )
     */
    public function showSeason(int $programId, int $seasonId, EpisodeRepository $episodeRepository): Response
    {

        $episodes = $episodeRepository->findEpisodesInSeason($seasonId);

         if (!$seasonId) {
            throw $this->createNotFoundException(
               'Nom invalide ou catégorie vide');
         }
        
        return $this->render('program/season_show.html.twig', ['episodes' => $episodes, 'seasonId' => $seasonId, 'programId' => $programId]);
    }

    /**
     * Undocumented function
     *
     * @param Program $program
     * @param Season $season
     * @param Episode $episode
     * @return void
     * 
     * @Route("/program/{program}/season/{season}/episode/{episode}", methods={"GET", "POST"}, name="program_episode_show" )
     */
   public function showEpisode(Program $program, Season $season, Episode $episode, EntityManagerInterface $entityManager, Request $request)
   {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }
    return $this->render('program/episode_show.html.twig.', 
        ['program' => $program, 'season' => $season, 'episode' => $episode, 'form' => $form->createView(),]);
   }

   /**
    * Undocumented function
    *
    * @param Request $request
    * @param Program $program
    * @param EntityManagerInterface $entityManager
    * @return Response
    *@Route("/program/{slug}/edit", methods = {"GET", "POST"}, name="program_edit")
    */
    public function edit(Request $request, Program $program_slug, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgramType::class, $program_slug);
        $form->handleRequest($request);

        if (!($this->getUser() == $program_slug->getOwner())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); 

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('program/edit.html.twig', ['program' => $program_slug, 'form' => $form,]);
   }

   #[Route('/program/{id}', name: 'program_delete', methods: ['POST'])]
   public function delete(Request $request, Program $program, EntityManagerInterface $entityManager)
   {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }
   }

   /**
    * 
    * @return void
    *@Route("/program/{id}/watchlist", methods = {"GET", "POST"}, name="program_watchlist")
    */
   public function addToWatchlist(Program $program, Request $request, EntityManagerInterface $entityManager)
   {
        if ($this->getUser()->isInWatchList($program)) {
            $this->getUser()->removeWatchlist($program);
        } else {
            $this->getUser()->addWatchlist($program);
        }

        $entityManager->flush();

        return $this->redirectToRoute('program_show', [
            'slug' => $program->getSlug()
        ]);
   }
}


