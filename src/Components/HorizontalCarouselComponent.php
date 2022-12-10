<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('horizontal-carousel')]
class HorizontalCarouselComponent
{
    public String $title;
    public Array $items;
}