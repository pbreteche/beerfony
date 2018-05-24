<?php

namespace App\Twig\filters;


class SuffixFilter
{
    public function apply(string $radix, string $suffix = '')
    {
        return $radix . $suffix;
    }

}