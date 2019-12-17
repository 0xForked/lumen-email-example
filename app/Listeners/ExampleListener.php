<?php

namespace App\Listeners;

use App\Mail\ExampleEmail;
use App\Events\ExampleEvent;
use App\Email;
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

        // run this action when email has queue
        if (!$data->queueable) {
            $message = (new ExampleEmail($data));
            Mail::to($data->to)->send($message);

            if (Mail::failures()) {
               return (Object)['callback' => false];
            }

            return (Object)['callback' => true];
        }

        // run this action when email setting has queue
        if ($data->queueable) {
            Email::create([
                'to' => $data->to,
                'subject' => $data->subject,
                'message' => $data->message
            ]);
        }
    }
}
