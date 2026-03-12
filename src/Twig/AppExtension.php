<?php

namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters():array{
        return [
        new TwigFilter('duree', [$this, 'duree']),
            ];
    }

    public function duree($minutes, $mode = 'short',$arrondi='nearest5'):string{
        if (!$minutes){
            return '0 min';
        }

        if ($arrondi == 'nearest5'){
            $minutes = round($minutes/5)*5;
        }
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;

        if ($h > 0) {
            return $h . 'h' . ($m ? $m : '');
        }

        return $m . 'min';


    }

}
