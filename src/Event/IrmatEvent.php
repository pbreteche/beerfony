<?php


namespace App\Event;


use App\Entity\Beer;
use Symfony\Component\EventDispatcher\Event;

class IrmatEvent extends Event
{
    const NAME = 'app.irmat';

    private $beer;

    public function __construct(Beer $beer)
    {
        $this->beer = $beer;
    }

    public function toast()
    {
        return $this->beer;
    }
}