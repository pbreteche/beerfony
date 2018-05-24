<?php

namespace App\Twig;


use App\Twig\filters\SuffixFilter;
use Twig\TwigFilter;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new TwigFilter('suffix', [SuffixFilter::class, 'apply']),
        ];
    }

}