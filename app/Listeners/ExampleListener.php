<?php

namespace App\Listeners;

use App\Mail\ExampleEmail;
use App\Events\ExampleEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;


class ExampleListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExampleEvent  $event
     * @return void
     */
    public function handle(ExampleEvent $event)
    {
        $data = (Object)$event->data;
        $message = (new ExampleEmail($data));
        Mail::to($data->to)->send($message);
    }
}
