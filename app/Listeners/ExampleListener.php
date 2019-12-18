<?php

namespace App\Listeners;

use App\Mail\ExampleEmail;
use App\Events\ExampleEvent;
use App\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;
use Bschmitt\Amqp\Facades\Amqp;

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

        if (!$data->queueable) {
            $message = (new ExampleEmail($data));
            Mail::to($data->to)->send($message);
            if (Mail::failures()) {
               return (Object)['callback' => false];
            }
            return (Object)['callback' => true];
        }

        // run this action when email setting has queue
        $driver = config('mail.queue_driver');
        if ($driver == 'mysql') $this->storeOnSql($data);
        if ($driver == 'redis') $this->storeOnRedis($data);
        if ($driver == 'rabbitmq') $this->storeOnRabbitMQ($data);
    }

    private function storeOnSql($data)
    {
        Email::create([
            'to' => $data->to,
            'subject' => $data->subject,
            'message' => $data->message
        ]);
    }

    private function storeOnRedis($data)
    {
        Redis::set("email:".time(), );
    }

    private function storeOnRabbitMQ($data)
    {
        Amqp::publish('/', json_encode($data), ['queue' => 'email_notify', 'vhost'   => '/email']);
    }

}
