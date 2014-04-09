<?php

require 'vendor/autoload.php';

//$app = new \Slim(array(
//    'debug' => true
//    , 'mode' => 'development'
//    , 'log.level' => \Slim\Log::DEBUG
//    , 'log.enabled' => true
//    , 'cookies.encrypt' => true
//    , 'cookies.lifetime' => '20 minutes'
//    , 'cookies.path' => '/'
//    , 'cookies.domain' => 'domain.com'
//    , 'cookies.secure' => false
//        ));
$app = new \Slim\Slim();
$app->get('/', function () {
    echo "Hello, Whazzupp";
});
$app->run();
// After instantiation
//$app->config('cookies.lifetime', '20 minutes');
// You may also define multiple settings at once using an associative array:
//$app->config(array(
//    'debug' => true,
//    'templates.path' => '../templates'
//));
// Define a HTTP GET route:
//$app->get('/hello/:name', function ($name) {
//    echo "Hello, $name";
//});
//Retrieve settings value
//$settingValue = $app->config('templates.path'); //returns "../templates"



// After instantiation
//$view = $app->view();
//$view->setTemplatesDirectory('./templates');
