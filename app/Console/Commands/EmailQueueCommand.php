<?php

namespace App\Console\Commands;

use App\Email;
use App\Events\ExampleEvent;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class EmailQueueCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:queue-action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Run email queue";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $driver = config('mail.queue_driver');

        if ($driver == 'mysql') {
            $emails = Email::where('status', 'PENDING')
                            ->orWhere('status', 'ERROR')
                            ->take(config('mail.queue_limit'))
                            ->get();
        }

        if ($driver == 'redis') {
            // todo
        }

        if ($driver == 'rabbitmq') {
            // todo
        }

        foreach ($emails as $email) {
            $data = [
                'queueable' => false,
                'to' => $email->to,
                'subject' => $email->subject,
                'message' => $email->message
            ];

            $action = event(new ExampleEvent($data));

            if ($action[0]->callback) {
                $emails = Email::find($email->id);
                $email->status = "SENT";
                $email->save();
            }

            if (!$action[0]->callback) {
                $emails = Email::find($email->id);
                $email->status = "ERROR";
                $email->save();
            }
        }

    }

}