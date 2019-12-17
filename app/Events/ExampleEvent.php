<?php

namespace App\Events;

use Illuminate\Support\Arr;

class ExampleEvent extends Event
{

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($dataInjection)
    {
        $this->data = $dataInjection;
    }
}
