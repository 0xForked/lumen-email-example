<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Mail\ExampleEmail;
use App\Events\ExampleEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


// ? How to: send email with this action
// run this app `php -S localhost:8080 -t public`
// or with nginx/apache2 on (linux os) and move to /var/www/html
// run {dns e.g {ip-address}:{port}}/send-email
$router->get('/send-email', function() {
    $to = 'aasumitro@gmail.com';
    $data = [
        'subject' => 'test send email',
        'message' => 'lorem just test send a email with lumen!'
    ];
    $message = (new ExampleEmail((Object)$data));
    Mail::to($to)->send($message);
    echo "email sent to $to";
});


// ? How to: send email with this action
// run this app `php -S localhost:8080 -t public`
// or with nginx/apache2 on (linux os) and move to /var/www/html
// run {dns e.g {ip-address}:{port}}/send-email-event
$router->get('send-email-event',  function() {
    $data = [
        'queueable' => false,
        'to' => 'aasumitro@gmail.com',
        'subject' => 'just test send email with event',
        'message' => 'lorem just test send a email with lumen!'
    ];
    event(new ExampleEvent($data));
    echo "email sent to {$data['to']} with event";
});


// ? How to: send email with this action
// run this app `php -S localhost:8080 -t public`
// or with nginx/apache2 on (linux os) and move to /var/www/html
// run {dns e.g {ip-address}:{port}}/send-email-queue to store data on storage
// ! add this action on crone job
// * php /var/www/html/artisan mail:queue-action >> /dev/null 2>&1
// * to test  php artisan mail:queue-action
// ! run this artisan command `php artisan mail:queue-action` just 1 time
// ! when queue driver is rabbitmq / when you user rabbit mq to handle a queue
// ! because rabbitmq can subscribe to specified topic/vhost and will not close the connection
// ! when this function `$resolver->stopWhenProcessed();` was disabled/commented
$router->post('send-email-queue', function(Request $request){
    $this->validate($request, [
        'to' => 'required|email',
        'subject' => 'required',
        'message' => 'required',
    ]);
    $data = [
        'queueable' => true,
        'to' => $request->to,
        'subject' => $request->subject,
        'message' => $request->message
    ];
    event(new ExampleEvent($data));
    echo "email sent to {$data['to']} with event and queue";
});
