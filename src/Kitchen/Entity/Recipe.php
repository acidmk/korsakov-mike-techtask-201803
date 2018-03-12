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

    public function toArray()
    {
        return [
            'title' => $this->title,
            'ingredients' => $this->ingredients
        ];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function getIngredientsStatus($repo)
    {
        $partlyFresh = false;

        foreach ($this->ingredients as $ingredient) {
            $entity = $repo->findIngredientByKey($ingredient);

            if (is_null($entity)) {
                return RecipeIngredientsStatus::MISSING_OR_OVERDUE;
            }

            $status = $entity->getStorageStatus();

            if ($status == IngredientStatus::NOT_FRESH) {
                $partlyFresh = true;
            }
            if ($status == IngredientStatus::OVERDUE) {
                return RecipeIngredientsStatus::MISSING_OR_OVERDUE;
            }
        }

        return $partlyFresh ? RecipeIngredientsStatus::PARTLY_FRESH : RecipeIngredientsStatus::ALL_FRESH;
    }
}
