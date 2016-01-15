<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add(null, __DIR__);

$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';