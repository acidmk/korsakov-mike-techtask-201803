<?php

use Silex\Application;
use Kitchen\Controller\LunchController;

define("ROOT_DIR", __DIR__ . "/../");

$app = new Application();

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['lunch.controller'] = function () use ($app) {
    return new LunchController();
};

$app->get('/lunch', "lunch.controller:get")->bind('lunch');

$app->get('/', function () use ($app) {
    $url = $app['url_generator']->generate('lunch');
    return $app->redirect($url);
});

return $app;
