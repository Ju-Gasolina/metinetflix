<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('watchlist-card')]
class WatchlistCardComponent
{
    public String $id;
    public String $idTMDB;
    public String $title;
    public String $posterPath;
    public String $pathName;
    public String $type;
    public String $status;

}