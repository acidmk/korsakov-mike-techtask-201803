<?php

namespace Kitchen\Tests;

use PHPUnit\Framework\TestCase;
use Kitchen\Repository\RecipeRepository;
use Kitchen\Repository\IngredientRepository;
use Kitchen\Model\RecipeIngredientsStatus;

class RecipeRepositoryTest extends TestCase
{
    /**
     * @dataProvider recipesProvider
     */
    public function testGetRecipeIngredientsStatus($ingredients, $recipes)
    {
        $ingredientsRepo = new IngredientRepository();
        $ingredientsRepo->loadIngredients($ingredients);
        $repo = new RecipeRepository($ingredientsRepo);
        $repo->loadRecipes($recipes);

        $this->assertEquals(
            $repo->findRecipeByKey('missing_ingredient')->getIngredientsStatus($ingredientsRepo),
            RecipeIngredientsStatus::MISSING_OR_OVERDUE
        );

        $this->assertEquals(
            $repo->findRecipeByKey('overdue_ingredient')->getIngredientsStatus($ingredientsRepo),
            RecipeIngredientsStatus::MISSING_OR_OVERDUE
        );

        $this->assertEquals(
            $repo->findRecipeByKey('not_fresh_ingredient')->getIngredientsStatus($ingredientsRepo),
            RecipeIngredientsStatus::PARTLY_FRESH
        );

        $this->assertEquals(
            $repo->findRecipeByKey('fresh_ingredient')->getIngredientsStatus($ingredientsRepo),
            RecipeIngredientsStatus::ALL_FRESH
        );
    }

    /**
     * @dataProvider recipesProvider
     */
    public function testGetRecipesForLunch($ingredients, $recipes)
    {
        $ingredientsRepo = new IngredientRepository();
        $ingredientsRepo->loadIngredients($ingredients);
        $repo = new RecipeRepository($ingredientsRepo);
        $repo->loadRecipes($recipes);

        $recipes = $repo->getRecipesForLunch();

        $this->assertEquals(count($recipes), 2);
        $this->assertEquals($recipes[0]['title'], 'fresh_ingredient');
        $this->assertEquals($recipes[1]['title'], 'not_fresh_ingredient');
    }

    public static function recipesProvider()
    {
        $now = new \DateTime();

        return [
            [
                'ingredients' => IngredientsRepositoryTest::ingredientsProvider()[0]['ingredients'],
                'recipes' => [
                    [
                        'title' => 'missing_ingredient',
                        'ingredients' => ['salami', 'fresh_ham']
                    ],
                    [
                        'title' => 'overdue_ingredient',
                        'ingredients' => ['fresh_ham', 'rotten_egg']
                    ],
                    [
                        'title' => 'not_fresh_ingredient',
                        'ingredients' => ['fresh_ham', 'dry_cheese']
                    ],
                    [
                        'title' => 'fresh_ingredient',
                        'ingredients' => ['fresh_ham']
                    ]
                ]
            ]
        ];
    }
}
