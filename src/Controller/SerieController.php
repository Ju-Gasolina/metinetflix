<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\FiltersType;
use App\Form\SearchType;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use App\Repository\WatchlistItemRepository;
use App\Repository\WatchlistRepository;
use App\Service\SerieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/serie')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'app_serie_index')]
    public function index(Request $request, SerieParsing $serieParsing, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SerieRepository $serieRepository): Response
    {

        $queryForm = $this->createForm(SearchType::class);
        $queryForm->handleRequest($request);

        $filtersForm = $this->createForm(FiltersType::class);
        $filtersForm->handleRequest($request);

        $page = $request->query->get('page');
        $currentQuery = $request->query->get('query');
        $currentFilters = $request->query->get('filters');

        if(empty($page))
        {
            return $this->redirectToRoute('app_serie_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        }
        else if($page < 1 || $page > 10)
        {
            throw $this->createNotFoundException('The page does not exist');
        }
        else
        {
            if ($queryForm->isSubmitted() && $queryForm->isValid()) {

                $data = $queryForm->getData();
                return $this->redirectToRoute('app_serie_index', ['page' => 1, 'query' => $data['query']], Response::HTTP_SEE_OTHER);

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


                return $this->redirectToRoute('app_serie_index', ['page' => 1, 'filters' => $currentFilters], Response::HTTP_SEE_OTHER);

            } else if ($currentQuery) {
                $seriesArray = $serieParsing->queryParsing($page, $currentQuery);

            } else if ($currentFilters) {

                $arrayFilters = array_map(function ($item) {
                    return $item[0];
                }, HeaderUtils::parseQuery($currentFilters, true, '!'));

                $seriesArray = $serieParsing->queryMaker(
                    $page,
                    $arrayFilters
                );

            } else {
                $seriesArray = $serieParsing->popularParsing($page);
                shuffle($seriesArray);
            }

            foreach($seriesArray as $serie) {
                $serie->setIsWatchlistItem($this->isWatchlistItem($serie->getId(), $security, $watchlistRepository, $watchlistItemRepository, $serieRepository));
            }



            return $this->render('serie/index.html.twig', [
                'controller_name' => 'CatalogController',
                'series' => $seriesArray,
                'currentPage' => $page,
                'queryForm' => $queryForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'currentFilters' => $currentFilters,
                'currentQuery' => $currentQuery,

            ]);
        }
    }

    #[Route('/new/{idTMDB}', name: 'app_serie_new', methods: ['GET'])]
    public function new(Int $idTMDB, SerieParsing $serieParsing, SerieRepository $serieRepository): Response
    {
        //Serie find request
        $serie = $serieRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(!isset($serie))
        {
            //Serie API request
            $result = $serieParsing->serieParsing($idTMDB);

            //Serie creation
            $serie = new Serie();
            $serie->setGenres(json_encode($result['genres']));
            $serie->setName($result['original_name']);
            $serie->setFirstAirDate($result['first_air_date']);
            $serie->setPosterPath($result['poster_path']);
            $serie->setIdTMDB($result['id']);
            $serieRepository->save($serie, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'serie', 'idEntity' => $serie->getId()]);
    }

    #[Route('/{id}', name: 'app_serie_show', methods: ['GET'])]
    public function show(int $id, SerieParsing $serieParsing, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SerieRepository $serieRepository, SeasonRepository $seasonRepository): Response
    {
        $serie = $serieParsing->serieParsing($id);

        $serie["isWatchlistItem"] = $this->isWatchlistItem($id, $security, $watchlistRepository, $watchlistItemRepository, $serieRepository);

        foreach($serie["seasons"] as $season) {
            $season->setIsWatchlistItem($this->isWatchlistItemSeason($season->getId(), $security, $watchlistRepository, $watchlistItemRepository, $seasonRepository));
        }

        return $this->render('serie/show.html.twig', [
            'serie' => $serie,
        ]);
    }

    private function isWatchlistItem(string $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SerieRepository $serieRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchSerie = $serieRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchSerie))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "serie" => $searchSerie->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }

    private function isWatchlistItemSeason(string $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SeasonRepository $seasonRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchSeason = $seasonRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchSeason))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "season" => $searchSeason->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }
}
