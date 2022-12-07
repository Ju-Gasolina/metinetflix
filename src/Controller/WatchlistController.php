<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WatchlistItemRepository;

#[Route('/watchlist')]
class WatchlistController extends AbstractController
{
    #[Route('/{id}', name: 'app_watchlist_show', methods: ['GET'])]
    public function show(Int $id, WatchlistItemRepository $watchlistItemRepository): Response
    {
        $watchlistItems = $watchlistItemRepository->findBy([
                "watchlist" => $id]);

        return $this->render('watchlist/show.html.twig', [
            'watchlistItems' => $watchlistItems,
        ]);
    }
}
