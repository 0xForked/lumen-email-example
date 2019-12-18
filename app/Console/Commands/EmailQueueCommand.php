<?php

namespace App\Console\Commands;

use App\Email;
use App\Events\ExampleEvent;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
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
    protected $description = "Run email queue (when driver is rabbitmq you can just running this action on first boot)";

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
        if ($driver == 'mysql') $this->queueOnSql();
        if ($driver == 'redis') $this->queueOnRedis();
        if ($driver == 'rabbitmq') $this->queueOnRabbitMQ();
    }

    private function queueOnSql()
    {
        $emails = Email::where('status', 'PENDING')
                        ->orWhere('status', 'ERROR')
                        ->take(config('mail.queue_limit'))
                        ->get();

        foreach ($emails as $email) {

            $action = event(new ExampleEvent($this->generateResource($email)));

            if (!$action[0]->callback) {
                $emails = Email::find($email->id);
                $email->status = "ERROR";
                $email->save();
            }

            $emails = Email::find($email->id);
            $email->status = "SENT";
            $email->save();
        }
    }

    private function queueOnRedis()
    {
        $keys = Redis::keys('email:*');
        foreach ($keys as $key => $value) {
            if ($key + 1 > config('mail.queue_limit')) break;

            $email = json_decode(Redis::get($value));

            $action = event(new ExampleEvent($this->generateResource($email)));

            if ($action[0]->callback) {
                Redis::del($value);
            }
        }
    }

    private function queueOnRabbitMQ()
    {
        Amqp::consume('email_notify', function ($message, $resolver) {
            $email = json_decode($message->body);
            $action = event(new ExampleEvent($this->generateResource($email)));
            if ($action[0]->callback) {
                var_dump("Email sent to {$email->to}");
            }
            $resolver->acknowledge($message);
            // $resolver->stopWhenProcessed();
        }, [
            'vhost'   => '/email'
        ]);
    }

    private function generateResource($email)
    {
        return [
            'queueable' => false,
            'to' => $email->to,
            'subject' => $email->subject,
            'message' => $email->message
        ];
    }

}