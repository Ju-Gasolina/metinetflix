<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\WatchlistRepository;
use phpDocumentor\Reflection\Types\Integer;

class WatchlistUtils
{

    public function getWatchlistIdByUser(User $user, WatchlistRepository $watchlistRepository):int{
        return $watchlistRepository->findOneBy(["user" => $user->getId()])->getId();

    }
}