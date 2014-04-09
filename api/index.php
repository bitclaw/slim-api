<?php

// http://127.0.0.1/slim-api/api/index.php/Daniel
// Make sure Virtual directory has AllowOverride All enabled

require '../vendor/autoload.php';

$app = new \Slim\Slim();
$app->get('/:name', function ($name) {
    echo "Hello, $name";
});
$app->run();
