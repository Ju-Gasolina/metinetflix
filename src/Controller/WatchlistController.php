<?php

namespace App\Controller;

use App\Form\WatchlistItemType;
use App\Repository\WatchlistItemRepository;
use App\Repository\WatchlistRepository;
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
use Symfony\Component\Security\Core\Security;

#[Route('/watchlist')]
class WatchlistController extends AbstractController
{
    #[Route('/', name: 'app_watchlist_index', methods: ['GET'])]
    public function index(Security $security, WatchlistRepository $watchlistRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $security->getUser();
        if (is_null($user)) return $this->redirectToRoute('app_home');
        $watchlist = $watchlistRepository->findOneBy(["user" => $user->getId()]);
        return $this->redirectToRoute('app_watchlist_show', ['id' => $watchlist->getId()]);
    }

    #[Route('/{id}', name: 'app_watchlist_show', methods: ['GET'])]
    public function show(int                     $id,
                         WatchlistItemRepository $watchlistItemRepository,
                         MovieParsing            $movieParsing,
                         SerieParsing            $serieParsing,
                         SeasonParsing           $seasonParsing,
                         EpisodeParsing          $episodeParsing,
                         SagaParsing             $sagaParsing,
                         WatchlistRepository     $watchlistRepository,
                         Security                $security,
                         Request                 $request,
                         WatchlistUtils $watchlistUtils
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (!$this->isUserVerified($id, $watchlistRepository, $security))
            return $this->redirectToRoute('app_watchlist_index');

        $watchlistItemId = $request->query->get('watchlistItemId');

        if ($watchlistItemId) {
            $watchlistItem = $watchlistItemRepository->findOneBy(['id' => $watchlistItemId]);
            $entityInformations = $watchlistUtils->getEntityInformationsByItem($watchlistItem);
            $watchlistItemForm = $this->createForm(WatchlistItemType::class, $watchlistItem, [
                'action' => $this->generateUrl('app_watchlist_item_modify', ['id' => $watchlistItemId]),
            ]);
            $watchlistItemForm->handleRequest($request);
        }

        if (isset($watchlistItemForm) && $watchlistItemForm->isSubmitted() && $watchlistItemForm->isValid()) {
            dd('validation');
        }

        $items = $watchlistItemRepository->findBy([
            "watchlist" => $id]);


        $watchlistItems = array();

        foreach ($items as $item) {
            switch ($item->getItemType()) {
                case 'movie':
                    $watchlistCard = $movieParsing->movieWatchlistCardParsing($item->getId(), $item->getMovie());
                    break;

                case 'serie':
                    $watchlistCard = $serieParsing->serieWatchlistCardParsing($item->getId(), $item->getSerie());
                    break;

                case 'season':
                    $watchlistCard = $seasonParsing->seasonWatchlistCardParsing($item->getId(), $item->getSeason());
                    break;

                case 'episode':
                    $watchlistCard = $episodeParsing->episodeWatchlistCardParsing($item->getId(), $item->getEpisode());
                    break;

                case 'saga':
                    $watchlistCard = $sagaParsing->sagaWatchlistCardParsing($item->getId(), $item->getSaga());
                    break;
            }

            $watchlistCard->setStatus($item->getStatus());
            $watchlistItems[] = $watchlistCard;
        }

        return $this->render('watchlist/show.html.twig', [
            'watchlistItems' => $watchlistItems,
            'watchlistItemForm' => isset($watchlistItemForm) ? $watchlistItemForm->createView() : null,
            'entityInformations' => $entityInformations ?? null,
            'watchlistId' => $id
        ]);
    }

    public function isUserVerified(int $idCalled, WatchlistRepository $watchlistRepository, Security $security)
    {
        $user = $security->getUser();
        $watchlist = $watchlistRepository->findOneBy(["id" => $idCalled]);
        if (is_null($watchlist) || $watchlist->getUser() !== $user) return false;
        return true;
    }
}
