<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\FiltersType;
use App\Form\SearchType;
use App\Repository\MovieRepository;
use App\Repository\WatchlistItemRepository;
use App\Repository\WatchlistRepository;
use App\Service\MovieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Saga;
use App\Repository\SagaRepository;
use App\Service\SagaParsing;
use Symfony\Component\Security\Core\Security;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'app_movie_index')]
    public function index(Request $request, MovieParsing $movieParsing, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, MovieRepository $movieRepository): Response
    {
        $queryForm = $this->createForm(SearchType::class);
        $queryForm->handleRequest($request);

        $filtersForm = $this->createForm(FiltersType::class);
        $filtersForm->handleRequest($request);

        $page = $request->query->get('page');
        $currentQuery = $request->query->get('query');
        $currentFilters = $request->query->get('filters');

        if (empty($page)) {
            return $this->redirectToRoute('app_movie_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        } 
        else if ($page < 1 || $page > 10) {
            throw $this->createNotFoundException('The page does not exist');
        } 
        else {
            if ($queryForm->isSubmitted() && $queryForm->isValid()) {

                $data = $queryForm->getData();
                return $this->redirectToRoute('app_movie_index', ['page' => 1, 'query' => $data['query']], Response::HTTP_SEE_OTHER);

            } else if ($filtersForm->isSubmitted() && $filtersForm->isValid()) {

                $data = $filtersForm->getData();
                $currentFilters = str_replace(" ", "", HeaderUtils::toString(
                    [
                        'primary_release_date.gte' => $data['minDate']->format('Y-m-d'),
                        'primary_release_date.lte' => $data['maxDate']->format('Y-m-d'),
                        'include_adult' => json_encode($data['includeAdult']),
                        'with_runtime.lte' => strval($data['maxTime']),
                        'sortBy' => $data['sortBy']
                    ],
                    '!'));

                return $this->redirectToRoute('app_movie_index', ['page' => 1, 'filters' => $currentFilters], Response::HTTP_SEE_OTHER);

            } 
            else if ($currentQuery) {
                $movieArray = $movieParsing->queryParsing($page, $currentQuery);

            } 
            else if ($currentFilters) {

                $arrayFilters = array_map(function ($item) {
                    return $item[0];
                }, HeaderUtils::parseQuery($currentFilters, true, '!'));

                $movieArray = $movieParsing->queryMaker(
                    $page,
                    $arrayFilters
                );

            } 
            else {
                $movieArray = $movieParsing->popularParsing($page);
                shuffle($movieArray);
            }

            foreach($movieArray as $movie) {
                $movie->setIsWatchlistItem($this->isWatchlistItem($movie->getId(), $security, $watchlistRepository, $watchlistItemRepository, $movieRepository));
            }

            return $this->render('movie/index.html.twig', [
                'controller_name' => 'CatalogController',
                'movies' => $movieArray,
                'currentPage' => $page,
                'queryForm' => $queryForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'currentFilters' => $currentFilters,
                'currentQuery' => $currentQuery,

            ]);
        }

    }


    #[Route('/new/{idTMDB}', name: 'app_movie_new', methods: ['GET'])]
    public function new(int $idTMDB, MovieParsing $movieParsing, MovieRepository $movieRepository, SagaParsing $sagaParsing, SagaRepository $sagaRepository): Response
    {
        //Movie find request
        $movie = $movieRepository->findOneBy(["idTMDB" => $idTMDB]);

        if (!isset($movie)) {
            //Movie API request
            $result = $movieParsing->movieParsing($idTMDB);

            //Movie creation
            $movie = new Movie();
            $movie->setGenres(json_encode($result['genres']));
            $movie->setName($result['original_title']);
            $movie->setDuration($result['runtime']);
            $movie->setReleaseDate($result['release_date']);
            $movie->setPosterPath($result['poster_path']);
            $movie->setIdTMDB($result['id']);

            if (!empty($result['belongs_to_collection']->id)) {
                //Saga find request
                $saga = $sagaRepository->findOneBy(["idTMDB" => $result['belongs_to_collection']->getId()]);

                if (!isset($saga)) {
                    //Saga API request
                    $result2 = $sagaParsing->sagaParsing($result['belongs_to_collection']->getId());

                    //Saga creation
                    $saga = new Saga();
                    $saga->setName($result2['name']);
                    $saga->setPosterPath($result2['poster_path']);
                    $saga->setIdTMDB($result2['id']);
                    $sagaRepository->save($saga, true);
                }

                $movie->setSaga($saga);
            }

            $movieRepository->save($movie, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'movie', 'idEntity' => $movie->getId()]);
    }

    #[Route('/{id}', name: 'app_movie_show', methods: ['GET'])]
    public function show(int $id, MovieParsing $movieParsing, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, MovieRepository $movieRepository): Response
    {
        $movie = $movieParsing->movieParsing($id);

        $movie["isWatchlistItem"] = $this->isWatchlistItem($id, $security, $watchlistRepository, $watchlistItemRepository, $movieRepository);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    private function isWatchlistItem(Int $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, MovieRepository $movieRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchMovie = $movieRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchMovie))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "movie" => $searchMovie->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }
}
