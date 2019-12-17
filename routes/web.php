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

use App\Events\ExampleEvent;
use App\Mail\ExampleEmail;
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
// run {dns e.g {ip-address}:{port}}/send-email
$router->get('send-email-event',  function() {
    $data = [
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
// ! add this action on crone job
// * php /{file location}(e.g /var/ww/html) /public/index.php/{function} >> /dev/null 2>&1
$router->get('send-email-queue', function() {
    // TODO: next
});