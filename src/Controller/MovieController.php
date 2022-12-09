<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MovieParsing;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'app_movie_index', methods: ['GET'])]
    public function index(Request $request, MovieParsing $movieParsing): Response
    {
        $page = $request->query->get('page');

        if(empty($page))
        {
            return $this->redirectToRoute('app_movie_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        }
        else if($page < 1 || $page > 10)
        {
            throw $this->createNotFoundException('The page does not exist');
        }
        else
        {
            return $this->render('movie/index.html.twig', [
                'movies' => $movieParsing->popularParsing($page),
                'currentPage' => $page
            ]);
        }
    }

    #[Route('/new/{idTMDB}', name: 'app_movie_new', methods: ['GET', 'POST'])]
    public function new(Int $idTMDB, MovieParsing $movieParsing, MovieRepository $movieRepository): Response
    {
        //Movie API request
        $result = $movieParsing->movieParsing($idTMDB);

        //Movie creation
        $movie = $movieRepository->findOneBy(["name" => $result['original_title']]);

        if(!isset($movie))
        {
            $movie = new Movie();
            $movie->setGenres(json_encode($result['genres']));
            $movie->setName($result['original_title']);
            $movie->setDuration($result['runtime']);
            $movieRepository->save($movie, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'movie', 'idEntity' => $movie->getId()]);
    }

    #[Route('/{id}', name: 'app_movie_show', methods: ['GET'])]
    public function show(int $id, MovieParsing $movieParsing): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movieParsing->movieParsing($id),
        ]);
    }
}
