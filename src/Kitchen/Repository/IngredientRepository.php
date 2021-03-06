<?php

namespace Kitchen\Repository;

use Kitchen\Entity\Ingredient;

class IngredientRepository
{
    private $ingredientEntities = [];

    public static function fromJson($path) {
        $instance = new self();

        $ingredients = json_decode(file_get_contents($path), true)['ingredients'];
        $instance->loadIngredients($ingredients);

        return $instance;
    }

    public function loadIngredients($ingredients)
    {
        $this->ingredientEntities = [];

        foreach ($ingredients as $ingredient) {
            $this->ingredientEntities[$ingredient['title']] =
                new Ingredient(
                    $ingredient['title'],
                    $ingredient['best-before'],
                    $ingredient['use-by']
                );
        }
    }

    public function findIngredientByKey($key)
    {
        return array_key_exists($key, $this->ingredientEntities) ? $this->ingredientEntities[$key] : null;
    }
}
