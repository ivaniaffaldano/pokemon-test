<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sum', [$this, 'sumExp']),
        ];
    }

    public function sumExp($pokemons)
    {
        $sum = 0;
        foreach($pokemons as $pokemon){
            $sum = $sum + $pokemon['exp'];
        }

        return $sum;
    }
}
