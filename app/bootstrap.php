<?php

use Silex\Application;
use Symfony\Component\Yaml\Yaml;
use Kitchen\Controller\LunchController;
use Kitchen\Repository\RecipeRepository;
use Kitchen\Repository\IngredientRepository;

define("ROOT_DIR", __DIR__ . "/../");

$app = new Application();

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['parameters'] = Yaml::parseFile(__DIR__ . '/config/parameters.yml')['parameters'];

$app['twitter.api'] = new Twitter(
    $app['parameters']['twitter_key'], 
    $app['parameters']['twitter_key_secret'], 
    $app['parameters']['twitter_token'], 
    $app['parameters']['twitter_token_secret']);

$app['ingredient.repository'] = function () use ($app) {
    $json_path = ROOT_DIR . $app['parameters']['ingredients_json_path'];
    return IngredientRepository::fromJson($json_path);
};

$app['recipe.repository'] = function () use ($app) {
    $json_path = ROOT_DIR . $app['parameters']['recipes_json_path'];
    return RecipeRepository::fromJson($app['ingredient.repository'], $json_path);
};

$app['lunch.controller'] = function () use ($app) {
    return new LunchController($app['recipe.repository']);
};

$app->get('/lunch', "lunch.controller:get")->bind('lunch');

$app->get('/', function () use ($app) {
    $url = $app['url_generator']->generate('lunch');
    return $app->redirect($url);
});

return $app;
