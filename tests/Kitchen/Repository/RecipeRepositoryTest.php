<?php

namespace Kitchen\Tests;

use PHPUnit\Framework\TestCase;
use Kitchen\Repository\RecipeRepository;
use Kitchen\Repository\IngredientRepository;
use Kitchen\Model\RecipeIngredientsStatus;

class RecipeRepositoryTest extends TestCase
{
    private $repo;

    public function setUp()
    {
        $now = new \DateTime();

        $ingredients = [
            array(
                'title' => 'fresh_ham',
                'best-before' => (clone $now)->add(new \DateInterval('P1D'))->format('Y-m-d'),
                'use-by' => (clone $now)->add(new \DateInterval('P10D'))->format('Y-m-d')),
            array(
                'title' => 'dry_cheese',
                'best-before' => (clone $now)->sub(new \DateInterval('P1D'))->format('Y-m-d'),
                'use-by' => (clone $now)->add(new \DateInterval('P10D'))->format('Y-m-d')),
            array(
                'title' => 'rotten_egg',
                'best-before' => (clone $now)->sub(new \DateInterval('P5D'))->format('Y-m-d'),
                'use-by' => (clone $now)->sub(new \DateInterval('P1D'))->format('Y-m-d'))
        ];

        $recipes = [
            array(
                'title' => 'missing_ingredient',
                'ingredients' => ['salami', 'fresh_ham']
            ),
            array(
                'title' => 'overdue_ingredient',
                'ingredients' => ['fresh_ham', 'rotten_egg']
            ),
            array(
                'title' => 'not_fresh_ingredient',
                'ingredients' => ['fresh_ham', 'dry_cheese']
            ),
            array(
                'title' => 'fresh_ingredient',
                'ingredients' => ['fresh_ham']
            )
        ];

        $ingredientsRepo = new IngredientRepository();
        $ingredientsRepo->loadIngredients($ingredients);
        $this->repo = new RecipeRepository($ingredientsRepo);
        $this->repo->loadRecipes($recipes);
    }

    public function testGetRecipeIngredientsStatus()
    {
        $this->assertEquals(
            $this->repo->getRecipeIngredientsStatus('missing_ingredient'), 
            RecipeIngredientsStatus::MISSING_OR_OVERDUE);

        $this->assertEquals(
            $this->repo->getRecipeIngredientsStatus('overdue_ingredient'), 
            RecipeIngredientsStatus::MISSING_OR_OVERDUE);

        $this->assertEquals(
            $this->repo->getRecipeIngredientsStatus('not_fresh_ingredient'), 
            RecipeIngredientsStatus::PARTLY_FRESH);

        $this->assertEquals(
            $this->repo->getRecipeIngredientsStatus('fresh_ingredient'), 
            RecipeIngredientsStatus::ALL_FRESH);
    }

    public function testGetRecipesForLunch()
    {
        $recipes = $this->repo->getRecipesForLunch();

        $this->assertEquals(count($recipes), 2);
        $this->assertEquals($recipes[0]['title'], 'fresh_ingredient');
        $this->assertEquals($recipes[1]['title'], 'not_fresh_ingredient');
    }
}
