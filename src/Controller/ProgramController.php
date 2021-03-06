<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
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
     * Undocumented function
     *
     * @param integer $id
     * @return Response
     * 
     * @Route("/program/{program}", methods={"GET"}, name="program_show")
     */
    public function show(int $id, SeasonRepository $seasonRepository): Response
    {
        $seasons = $seasonRepository->findSeasonInProgram($id);

         if (!$id) {
            throw $this->createNotFoundException(
               'Nom invalide ou catégorie vide');
         }

        return $this->render('program/show.html.twig', ['seasons' => $seasons, 'id' => $id]);
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


