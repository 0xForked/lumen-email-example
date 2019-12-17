<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ExampleEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $data;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Object $testInject)
    {
        $this->data = $testInject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data->subject)
                    ->view('emails.example');
    }
}