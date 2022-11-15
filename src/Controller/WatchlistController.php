<?php

namespace App\Controller;

use App\Entity\Watchlist;
use App\Form\WatchlistType;
use App\Repository\WatchlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/watchlist')]
class WatchlistController extends AbstractController
{
    #[Route('/', name: 'app_watchlist_index', methods: ['GET'])]
    public function index(WatchlistRepository $watchlistRepository): Response
    {
        return $this->render('watchlist/index.html.twig', [
            'watchlists' => $watchlistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_watchlist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WatchlistRepository $watchlistRepository): Response
    {
        $watchlist = new Watchlist();
        $form = $this->createForm(WatchlistType::class, $watchlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $watchlistRepository->save($watchlist, true);

            return $this->redirectToRoute('app_watchlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('watchlist/new.html.twig', [
            'watchlist' => $watchlist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_watchlist_show', methods: ['GET'])]
    public function show(Watchlist $watchlist): Response
    {
        return $this->render('watchlist/show.html.twig', [
            'watchlist' => $watchlist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_watchlist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Watchlist $watchlist, WatchlistRepository $watchlistRepository): Response
    {
        $form = $this->createForm(WatchlistType::class, $watchlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $watchlistRepository->save($watchlist, true);

            return $this->redirectToRoute('app_watchlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('watchlist/edit.html.twig', [
            'watchlist' => $watchlist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_watchlist_delete', methods: ['POST'])]
    public function delete(Request $request, Watchlist $watchlist, WatchlistRepository $watchlistRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$watchlist->getId(), $request->request->get('_token'))) {
            $watchlistRepository->remove($watchlist, true);
        }

        return $this->redirectToRoute('app_watchlist_index', [], Response::HTTP_SEE_OTHER);
    }
}
