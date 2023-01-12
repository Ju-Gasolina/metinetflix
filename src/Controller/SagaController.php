<?php

namespace App\Controller;

use App\Entity\Saga;
use App\Form\SagaType;
use App\Repository\SagaRepository;
use App\Repository\WatchlistItemRepository;
use App\Repository\WatchlistRepository;
use App\Service\SagaParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Movie;
use App\Service\MovieParsing;
use App\Repository\MovieRepository;

use App\Entity\Card;
use Symfony\Component\Security\Core\Security;

#[Route('/saga')]
class SagaController extends AbstractController
{
    #[Route('/', name: 'app_saga_index', methods: ['GET'])]
    public function index(Request $request, SagaRepository $sagaRepository, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository): Response
    {
        $page = $request->query->get('page');

        if(empty($page))
        {
            return $this->redirectToRoute('app_saga_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        }
        else if($page < 1 || $page > 10)
        {
            throw $this->createNotFoundException('The page does not exist');
        }
        else
        {
            $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

            $sagas = array();

            $items = $sagaRepository->findWithOffsetAndLimit((($page-1)*20),20);

            foreach($items as $item)
            {
                $client = HttpClient::create();
                $response = $client->request('GET', 'https://api.themoviedb.org/3/collection/'.$item->getIdTMDB().'?api_key='.$apiKey.'&language=fr-FR');
                $item = $response->toArray();

                $saga = new Card(
                    $item['id'],
                    $item['name'],
                    $item['release_date'] ?? 0,
                    'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                    'app_saga_show',
                    'saga',
                    null,
                    null);
                $sagas[] = $saga;
            }

            foreach($sagas as $saga) {
                $saga->setIsWatchlistItem($this->isWatchlistItem($saga->getId(), $security, $watchlistRepository, $watchlistItemRepository, $sagaRepository));
            }

            return $this->render('saga/index.html.twig', [
                'sagas' => $sagas,
                'currentPage' => $page
            ]);
        }
    }

    #[Route('/new/{idTMDB}', name: 'app_saga_new', methods: ['GET'])]
    public function new(Int $idTMDB, SagaParsing $sagaParsing, SagaRepository $sagaRepository, MovieParsing $movieParsing, MovieRepository $movieRepository): Response
    {
        //Saga find request
        $saga = $sagaRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(!isset($saga))
        {
            //Saga API request
            $result = $sagaParsing->sagaParsing($idTMDB);

            //Saga creation
            $saga = new Saga();
            $saga->setName($result['name']);
            $saga->setIdTMDB($result['id']);
            $saga->setPosterPath($result['poster_path']);
            $sagaRepository->save($saga, true);

            foreach($result['movies'] as $part)
            {
                //Movie find request
                $movie = $movieRepository->findOneBy(["idTMDB" => $part->getId()]);

                if(!isset($movie))
                {
                    //Movie API request
                    $result2 = $movieParsing->movieParsing($part->getId());

                    //Movie creation
                    $movie = new Movie();
                    $movie->setGenres(json_encode($result2['genres']));
                    $movie->setName($result2['original_title']);
                    $movie->setDuration($result2['runtime']);
                    $movie->setReleaseDate($result2['release_date']);
                    $movie->setPosterPath($result2['poster_path']);
                    $movie->setIdTMDB($result2['id']);
                    $movie->setSaga($saga);
                    $movieRepository->save($movie, true);
                }
            }
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'saga', 'idEntity' => $saga->getId()]);
    }

    #[Route('/fill-db/{popularPage}', name: 'app_saga_fill_db', methods: ['GET'])]
    public function fillDB(int $popularPage, SagaParsing $sagaParsing, SagaRepository $sagaRepository): Response
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';
        $flush = false;
        $cpt = $popularPage;
        $limit = 5;

        do
        {
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/popular?api_key='.$apiKey.'&language=fr-FR&page='.$cpt);
            $items = $response->toArray();

            foreach($items['results'] as $item) {
                $client2 = HttpClient::create();
                $response2 = $client2->request('GET', 'https://api.themoviedb.org/3/movie/'.$item['id'].'?api_key='.$apiKey.'&language=fr-FR');
                $item2 = $response2->toArray();

                if(isset($item2['belongs_to_collection'])) {
                    //Saga find request
                    $saga = $sagaRepository->findOneBy(["idTMDB" => $item2['belongs_to_collection']['id']]);

                    if(!isset($saga))
                    {
                        $flush = true;

                        //Saga API request
                        $result = $sagaParsing->sagaParsing($item2['belongs_to_collection']['id']);

                        //Saga creation
                        $saga = new Saga();
                        $saga->setName($result['name']);
                        $saga->setIdTMDB($result['id']);
                        $saga->setPosterPath($result['poster_path']);
                        $sagaRepository->save($saga);
                    }
                }
            }

            if($flush)
            {
                $sagaRepository->flush();
                $flush = false;
            }

            $cpt++;
        }while($cpt < $limit);

        $nextPopularPage = $popularPage + $limit;

        return $this->redirectToRoute('app_saga_fill_db', ['popularPage' => $nextPopularPage]);
    }

    #[Route('/{id}', name: 'app_saga_show', methods: ['GET'])]
    public function show(int $id, SagaParsing $sagaParsing, SagaRepository $sagaRepository, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository): Response
    {
        $saga = $sagaParsing->sagaParsing($id);

        $saga["isWatchlistItem"] = $this->isWatchlistItem($id, $security, $watchlistRepository, $watchlistItemRepository, $sagaRepository);

        return $this->render('saga/show.html.twig', [
            'saga' => $saga,
        ]);
    }

    private function isWatchlistItem(Int $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SagaRepository $sagaRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchSaga = $sagaRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchSaga))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "saga" => $searchSaga->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }
}
