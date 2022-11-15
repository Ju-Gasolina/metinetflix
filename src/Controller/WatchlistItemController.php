<?php

namespace App\Controller;

use App\Entity\WatchlistItem;
use App\Form\WatchlistItemType;
use App\Repository\WatchlistItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/new', name: 'app_watchlist_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WatchlistItemRepository $watchlistItemRepository): Response
    {
        $watchlistItem = new WatchlistItem();
        $form = $this->createForm(WatchlistItemType::class, $watchlistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $watchlistItemRepository->save($watchlistItem, true);

            return $this->redirectToRoute('app_watchlist_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('watchlist_item/new.html.twig', [
            'watchlist_item' => $watchlistItem,
            'form' => $form,
        ]);
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
