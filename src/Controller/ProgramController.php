<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\ProgramType;
use Symfony\Component\Mime\Email;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
        $programs = $this->getDoctrine()
             ->getRepository(Program::class)
             ->findAll();

        return $this->render('program/index.html.twig', ['programs' => $programs]);
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

            $entityManager->persist($program);

            $entityManager->flush();

            $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to('yannmalfer@gmail.com')
                    ->subject('une nouvelle série vient d\'être publiée')
                    ->html($this->renderView('Program/newProgramEmail.html.twig' , ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
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
     * @Route("/program/{program}/season/{season}/episode/{episode}", methods={"GET"}, name="program_episode_show" )
     */
   public function showEpisode(Program $program, Season $season, Episode $episode)
   {
    return $this->render('program/episode_show.html.twig.', ['program' => $program, 'season' => $season, 'episode' => $episode]);
   }
}


