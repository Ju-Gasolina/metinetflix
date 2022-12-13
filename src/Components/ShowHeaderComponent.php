<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('show-header')]
class ShowHeaderComponent
{
    public String $posterPath;
    public String $name;
    public String $type;
    public String $id;
}