<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('card')]
class CardComponent
{
    public String $id;
    public String $title;
    public String $posterPath;
    public String $pathName;
    public String $type;
    public ?float $popularity = null;
    public ?float $markAverage = null;
    public bool $isWatchlistItem;
}