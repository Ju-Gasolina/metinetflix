<?php

namespace App\Controller;

use App\Entity\WatchlistItem;
use App\Repository\WatchlistItemRepository;
use App\Form\WatchlistItemType;
use App\Service\EpisodeParsing;
use App\Service\MovieParsing;
use App\Service\SagaParsing;
use App\Service\SeasonParsing;
use App\Service\SerieParsing;
use App\Service\WatchlistUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WatchlistRepository;
use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\SagaRepository;
use Symfony\Component\Security\Core\Security;

#[Route('/watchlist/item')]
class WatchlistItemController extends AbstractController
{
    #[Route('/new/{type}/{idTMDB}', name: 'app_watchlist_item_new', methods: ['GET'])]
    public function new(String $type, String $idTMDB): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if($type == "movie")
        {
            return $this->redirectToRoute('app_movie_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "serie")
        {
            return $this->redirectToRoute('app_serie_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "season")
        {
            return $this->redirectToRoute('app_season_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "episode")
        {
            return $this->redirectToRoute('app_episode_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "saga")
        {
            return $this->redirectToRoute('app_saga_new', ['idTMDB' => $idTMDB]);
        }
        else
        {
            return $this->redirect('/');
        }
    }

    #[Route('/add/{type}/{idEntity}', name: 'app_watchlist_item_add', methods: ['GET'])]
    public function add(String $type,
                        Int $idEntity,
                        WatchlistRepository $watchlistRepository,
                        WatchlistItemRepository $watchlistItemRepository,
                        MovieRepository $movieRepository,
                        SerieRepository $serieRepository,
                        SeasonRepository $seasonRepository,
                        EpisodeRepository $episodeRepository,
                        SagaRepository $sagaRepository,
                        WatchlistUtils $watchlistUtils,
                        Request $request,
                        Security $security
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $user = $security->getUser();
        $watchlist = $watchlistRepository->findOneBy(['id'=>$watchlistUtils->getWatchlistIdByUser($user,$watchlistRepository)]);
        $watchlistItem = $watchlistItemRepository->findOneBy([$type => $idEntity]);

        if(!isset($watchlistItem)) {
            $watchlistItem = new WatchlistItem();
            $watchlistItem->setWatchlist($watchlist);
            $watchlistItem->setItemType($type);
            $watchlistItem->setStatus('Plan to watch');

            if($type == "movie") {
                $movie = $movieRepository->find($idEntity);
                $watchlistItem->setMovie($movie);
            }
            else if($type == "serie")
            {
                $serie = $serieRepository->find($idEntity);
                $watchlistItem->setSerie($serie);
            }
            else if($type == "season")
            {
                $season = $seasonRepository->find($idEntity);
                $watchlistItem->setSeason($season);
            }
            else if($type == "episode")
            {
                $episode = $episodeRepository->find($idEntity);
                $watchlistItem->setEpisode($episode);
            }
            else if($type == "saga")
            {
                $saga = $sagaRepository->find($idEntity);
                $watchlistItem->setSaga($saga);
            }
            else
            {
                return $this->redirect('/');
            }

            $watchlistItemRepository->save($watchlistItem, true);
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/{id}', name: 'app_watchlist_item_delete',methods: ['GET'])]
    public function delete(Int $id, WatchlistItemRepository $watchlistItemRepository ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $item = $watchlistItemRepository->find($id);
        $watchlistId = $watchlistItemRepository->find($id)->getWatchlist()->getId();
        $watchlistItemRepository->remove($item, true);
        return $this->redirectToRoute('app_watchlist_show', ['id' => $watchlistId]);
    }

    #[Route('/delete/{idTMDB}/{type}', name: 'app_watchlist_item_deleteByIdTMDB',methods: ['GET'])]
    public function deleteByIdTMDB(string $idTMDB, string $type, Security $security, WatchlistItemRepository $watchlistItemRepository, WatchlistRepository $watchlistRepository, Request $request, MovieRepository $movieRepository, SerieRepository $serieRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository, SagaRepository $sagaRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $watchlistId = $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()])->getId();

        if($type == "movie"){
            $id = $movieRepository->findOneBy(["idTMDB" => $idTMDB])->getId();
            $item = $watchlistItemRepository->findOneBy(["watchlist" => $watchlistId, "movie" => $id]);
        }
        else if($type == "serie") {
            $id = $serieRepository->findOneBy(["idTMDB" => $idTMDB])->getId();
            $item = $watchlistItemRepository->findOneBy(["watchlist" => $watchlistId, "serie" => $id]);
        }
        else if($type == "season") {
            $id = $seasonRepository->findOneBy(["idTMDB" => $idTMDB])->getId();
            $item = $watchlistItemRepository->findOneBy(["watchlist" => $watchlistId, "season" => $id]);
        }
        else if($type == "episode") {
            $id = $episodeRepository->findOneBy(["idTMDB" => $idTMDB])->getId();
            $item = $watchlistItemRepository->findOneBy(["watchlist" => $watchlistId, "episode" => $id]);
        }
        else if($type == "saga") {
            $id = $sagaRepository->findOneBy(["idTMDB" => $idTMDB])->getId();
            $item = $watchlistItemRepository->findOneBy(["watchlist" => $watchlistId, "saga" => $id]);
        }

        $watchlistItemRepository->remove($item, true);

        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/modify/{id}/{status}', name: 'app_watchlist_item_modify_status',methods: ['GET'])]
    public function modifyStatus(Int $id, String $status, WatchlistItemRepository $watchlistItemRepository ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $item = $watchlistItemRepository->find($id);
        $item->setStatus($status);
        $watchlistItemRepository->save($item, true);
        $watchlistId = $item->getWatchlist()->getId();
        return $this->redirectToRoute('app_watchlist_show', ['id' => $watchlistId]);
    }

    #[Route('/modify/{id}', name: 'app_watchlist_item_modify',methods: ['GET', 'POST'])]
    public function modify(Request $request,WatchlistItem $watchlistItem, WatchlistItemRepository $watchlistItemRepository){

        $form = $this->createForm(WatchlistItemType::class, $watchlistItem);
        $form->handleRequest($request);
        $watchlistItemRepository->save($watchlistItem, true);
        return $this->redirectToRoute('app_watchlist_index', ['id' => $watchlistItem->getWatchlist()->getId()]);
    }

}