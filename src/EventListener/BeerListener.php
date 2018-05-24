<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 27/04/18
 * Time: 14:22
 */

namespace App\EventListener;


use App\Event\IrmatEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BeerListener
{
    private $persistentEvent = 'toto';
    public function onKernelRequest(GetResponseEvent $event)
    {
        dump($event);
        $this->persistentEvent = 'titi';
    }

    public function onAppIrmat(IrmatEvent $event)
    {
        dump($this->persistentEvent);
        dump($event);
    }
}