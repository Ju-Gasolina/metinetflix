<?php

namespace App\Controller;

use App\Repository\WatchlistRepository;
use JetBrains\PhpStorm\NoReturn;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WatchlistItemRepository;

use App\Service\MovieParsing;
use App\Service\SerieParsing;
use App\Service\SeasonParsing;
use App\Service\EpisodeParsing;
use App\Service\SagaParsing;
use Symfony\Component\Security\Core\Security;
use function PHPUnit\Framework\isNull;

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
                         Security                $security
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (!$this->isUserVerified($id, $watchlistRepository, $security))
            return $this->redirectToRoute('app_watchlist_index');

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

            $watchlistItems[] = $watchlistCard;
        }

        return $this->render('watchlist/show.html.twig', [
            'watchlistItems' => $watchlistItems,
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
