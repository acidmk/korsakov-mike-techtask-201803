<?php

namespace Kitchen\Repository;

use Kitchen\Entity\Recipe;
use Kitchen\Entity\Ingredient;
use Kitchen\Model\IngredientStatus;
use Kitchen\Model\RecipeIngredientsStatus;

class RecipeRepository
{
    private $recipeEntities = array();
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
        $recipeEntities = array();

        foreach ($data as $recipe) {
            $this->recipeEntities[$recipe['title']] = new Recipe($recipe['title'], $recipe['ingredients']);
        }
    }

    function getRecipeIngredientsStatus($key)
    {
        $recipe = $this->recipeEntities[$key];
        $ingredients = $recipe->getIngredients();
        $partlyFresh = false;

        foreach ($ingredients as $ingredient) {
            $entity = $this->ingredientsRepo->findIngredientByKey($ingredient);
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

    public function getRecipesForLunch()
    {
        $recipes = array();

        foreach ($this->recipeEntities as $recipe) {
            $status = $this->getRecipeIngredientsStatus($recipe->getTitle());

            if ($status == RecipeIngredientsStatus::ALL_FRESH) {
                array_unshift($recipes, $recipe->toArray());
            } else if ($status == RecipeIngredientsStatus::PARTLY_FRESH) {
                $recipes[] = $recipe->toArray();
            }
        }

        return $recipes;
    }
}
