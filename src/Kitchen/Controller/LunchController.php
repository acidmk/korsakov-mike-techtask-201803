<?php

namespace Kitchen\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Kitchen\Repository\RecipeRepository;

class LunchController
{
    protected $repo;

    public function __construct(RecipeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function get(Request $request, Application $app)
    {
        return $app->json($this->repo->getRecipesForLunch(), Response::HTTP_OK);
    }
}
