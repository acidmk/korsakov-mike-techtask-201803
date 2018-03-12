<?php

namespace Kitchen\Repository;

use Kitchen\Entity\Recipe;
use Kitchen\Entity\Ingredient;
use Kitchen\Model\IngredientStatus;
use Kitchen\Model\RecipeIngredientsStatus;

class RecipeRepository
{
    private $recipeEntities = [];
    private $ingredientsRepo;

    public function __construct($repo)
    {
        $this->ingredientsRepo = $repo;
    }

    public static function fromJson($repo, $path)
    {
        $instance = new self($repo);
        
        $recipes = json_decode(file_get_contents($path), true)['recipes'];
        $instance->loadRecipes($recipes);

        return $instance;
    }

    public function loadRecipes($data)
    {
        $recipeEntities = [];

        foreach ($data as $recipe) {
            $this->recipeEntities[$recipe['title']] = new Recipe($recipe['title'], $recipe['ingredients']);
        }
    }

    public function findRecipeByKey($key)
    {
        return array_key_exists($key, $this->recipeEntities) ? $this->recipeEntities[$key] : null;
    }

    public function getRecipesForLunch()
    {
        $recipes = [];

        foreach ($this->recipeEntities as $recipe) {
            $status = $recipe->getIngredientsStatus($this->ingredientsRepo);

            if ($status == RecipeIngredientsStatus::ALL_FRESH) {
                array_unshift($recipes, $recipe->toArray());
            } else if ($status == RecipeIngredientsStatus::PARTLY_FRESH) {
                $recipes[] = $recipe->toArray();
            }
        }

        return $recipes;
    }
}
