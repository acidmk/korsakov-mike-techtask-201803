<?php

namespace Kitchen\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Kitchen\Repository\RecipeRepository;

class LunchController
{
    public function __construct()
    {

    }

    public function get(Request $request, Application $app)
    {
        return $app->json('test', Response::HTTP_OK);
    }
}
