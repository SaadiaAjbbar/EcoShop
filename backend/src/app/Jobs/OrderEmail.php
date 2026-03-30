<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class OrderEmail implements ShouldQueue
{
    use Queueable;

    private $order;
    /**
     * Create a new job instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // hna t9der t-st3ml l-mailer dyal Laravel bach t-sift email
        // mail::to($event->order->user->email)->send(new OrderEmail($event->order));

        Mail::to($this->order->user->email)->send(new \App\Mail\OrderEmail($this->order));
        $products = $this->order->items->pluck('product')->map(function ($product) {
            $product->quantity -= $this->order->items()->where('product_id', $product->id)->first()->quantity;
            $product->save();
            return $product;
        });



    }
}
