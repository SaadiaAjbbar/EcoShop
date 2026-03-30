<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\OrderEmail;

class OrderEmailListener
{

        /**
        * Handle the event.
        */
    public function handle(OrderPlaced $event): void
    {
        OrderEmail::dispatch($event->order);
    }
}
