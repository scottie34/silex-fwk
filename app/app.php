<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register services
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['silexfwk.postController'] = $app->share(function() use ($app) {
    return new \SilexFwk\Controller\PostController($app['db']);
});

// creates the decorated restController
$app['microrest.restController'] = $app->share(function() use ($app) {
    $restController = new  Marmelab\Microrest\RestController($app['db']);
    $restController->registerDecorator('posts', new \SilexFwk\Decorator\PostDecorator());
    return $restController;
});


$app->register(new Marmelab\Microrest\MicrorestServiceProvider(), array(
    'microrest.config_file' => __DIR__ . '/api.raml'
));

// avoid error in MicrorestServiceProdiver... don't seem to be useful in our case
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.options' => array('cache' => __DIR__.'/../tmp/cache/twig'),
));

$app['microrest.restController'];






