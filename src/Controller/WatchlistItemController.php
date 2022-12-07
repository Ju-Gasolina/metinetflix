<?php

namespace App\Controller;

use App\Entity\WatchlistItem;
use App\Form\WatchlistItemType;
use App\Repository\WatchlistItemRepository;
use App\Service\MovieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Movie;
use App\Repository\MovieRepository;

use App\Repository\WatchlistRepository;

#[Route('/watchlist/item')]
class WatchlistItemController extends AbstractController
{
    #[Route('/', name: 'app_watchlist_item_index', methods: ['GET'])]
    public function index(WatchlistItemRepository $watchlistItemRepository): Response
    {
        return $this->render('watchlist_item/index.html.twig', [
            'watchlist_items' => $watchlistItemRepository->findAll(),
        ]);
    }

    #[Route('/new/{type}/{id}', name: 'app_watchlist_item_new', methods: ['GET', 'POST'])]
    public function new(String $type,
                        Int $id,
                        WatchlistItemRepository $watchlistItemRepository,
                        MovieParsing $movieParsing,
                        MovieRepository $movieRepository,
                        WatchlistRepository $watchlistRepository,
    ): Response
    {
        if($type == "movie")
        {
            //Movie API request
            $result = $movieParsing->movieParsing($id);

            if(!isset($movie))
            {
                $movie = new Movie();
                $movie->setName($result['original_title']);
                $movie->setDuration($result['runtime']);
                $movieRepository->save($movie, true);
            }

            //Movie creation
            $movie = $movieRepository->findOneBy(["name" => $result['original_title']]);

            if(!isset($movie))
            {
                $movie = new Movie();
                $movie->setGenres("ss");
                $movie->setName($result['original_title']);
                $movie->setDuration($result['runtime']);
                $movieRepository->save($movie, true);
            }

            //Watchlist find request
            $watchlist = $watchlistRepository->find(1);

            //WatchlistItem creation
            $watchlistItem = $watchlistItemRepository->findOneBy(["movie" => $movie->getId()]);

            if(!isset($watchlistItem))
            {
                $watchlistItem = new WatchlistItem();
                $watchlistItem->setWatchlist($watchlist);
                $watchlistItem->setItemType("movie");
                $watchlistItem->setMovie($movie);
                $watchlistItemRepository->save($watchlistItem, true);
            }

            return $this->redirectToRoute('app_watchlist_show', ['id' => $watchlist->getId()]);
        }
        else
        {
            return $this->redirect('/');
        }
    }

    #[Route('/{id}', name: 'app_watchlist_item_show', methods: ['GET'])]
    public function show(WatchlistItem $watchlistItem): Response
    {
        return $this->render('watchlist_item/show.html.twig', [
            'watchlist_item' => $watchlistItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_watchlist_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WatchlistItem $watchlistItem, WatchlistItemRepository $watchlistItemRepository): Response
    {
        $form = $this->createForm(WatchlistItemType::class, $watchlistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $watchlistItemRepository->save($watchlistItem, true);

            return $this->redirectToRoute('app_watchlist_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('watchlist_item/edit.html.twig', [
            'watchlist_item' => $watchlistItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_watchlist_item_delete', methods: ['POST'])]
    public function delete(Request $request, WatchlistItem $watchlistItem, WatchlistItemRepository $watchlistItemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$watchlistItem->getId(), $request->request->get('_token'))) {
            $watchlistItemRepository->remove($watchlistItem, true);
        }

        return $this->redirectToRoute('app_watchlist_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
