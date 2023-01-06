<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('carousel-card')]
class CarouselCardComponent
{
    public String $id;
    public String $title;
    public String $posterPath;
    public String $pathName;
    public String $type;
    public float $markAverage;
    public float $popularity;
}