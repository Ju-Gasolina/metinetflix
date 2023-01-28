<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\WatchlistItem;
use App\Repository\WatchlistRepository;
use phpDocumentor\Reflection\Types\Integer;

class WatchlistUtils
{
    public function getWatchlistIdByUser(User $user, WatchlistRepository $watchlistRepository):int{
        return $watchlistRepository->findOneBy(["user" => $user->getId()])->getId();

    }

    public function getEntityInformationsByItem(WatchlistItem $item): array{
        switch ($item->getItemType()){
            case 'serie':
                return ['poster_path' => $item->getSerie()->getPosterPath(), 'name' => $item->getSerie()->getName()];
            case 'movie':
                return ['poster_path' => $item->getMovie()->getPosterPath(), 'name' => $item->getMovie()->getName()];
            case 'episode':
                return ['poster_path' => $item->getEpisode()->getPosterPath(), 'name' => $item->getEpisode()->getName()];
            case 'saga':
                return ['poster_path' => $item->getSaga()->getPosterPath(), 'name' => $item->getSaga()->getName()];
            case 'season':
                return ['poster_path' => $item->getSeason()->getPosterPath(), 'name' => $item->getSeason()->getName()];
            default:
                return [];
        }
    }
}