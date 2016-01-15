<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';

list($_, $method, $path) = $argv;
$request = Symfony\Component\HttpFoundation\Request::create($path, $method);
$app->run($request);