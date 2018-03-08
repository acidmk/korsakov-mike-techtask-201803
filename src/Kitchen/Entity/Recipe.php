<?php

namespace Kitchen\Entity;

use Kitchen\Model\IngredientStatus;
use Kitchen\Model\RecipeIngredientsStatus;

class Recipe
{
    private $title;
    private $ingredients;

    public function __construct($title, $ingredients)
    {
        $this->title = $title;
        $this->ingredients = $ingredients;
    }

    public function getTitle() 
    {
        return $this->title;
    }

    public function getIngredients() 
    {
        return $this->ingredients;
    }
}
