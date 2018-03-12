<?php

namespace Kitchen\Tests;

use PHPUnit\Framework\TestCase;
use Kitchen\Repository\IngredientRepository;
use Kitchen\Model\IngredientStatus;

class IngredientsRepositoryTest extends TestCase
{
    /**
     * @dataProvider ingredientsProvider
     */
    public function testFindIngredientByKey($ingredients)
    {
        $repo = new IngredientRepository();
        $repo->loadIngredients($ingredients);

        $ingredient = $repo->findIngredientByKey('fresh_ham');
        $this->assertEquals($ingredient->toArray(), $ingredients[0]);
    }

    /**
     * @dataProvider ingredientsProvider
     */
    public function testIngredientStatus($ingredients)
    {
        $repo = new IngredientRepository();
        $repo->loadIngredients($ingredients);

        $ingredient = $repo->findIngredientByKey('fresh_ham');
        $this->assertEquals($ingredient->getStorageStatus(), IngredientStatus::FRESH);

        $ingredient = $repo->findIngredientByKey('dry_cheese');
        $this->assertEquals($ingredient->getStorageStatus(), IngredientStatus::NOT_FRESH);

        $ingredient = $repo->findIngredientByKey('rotten_egg');
        $this->assertEquals($ingredient->getStorageStatus(), IngredientStatus::OVERDUE);
    }

    public static function ingredientsProvider()
    {
        $now = new \DateTime();

        return [
             [ 'ingredients' => [
                    [
                        'title' => 'fresh_ham',
                        'best-before' => (clone $now)->add(new \DateInterval('P1D'))->format('Y-m-d'),
                        'use-by' => (clone $now)->add(new \DateInterval('P10D'))->format('Y-m-d')
                    ],
                    [
                        'title' => 'dry_cheese',
                        'best-before' => (clone $now)->sub(new \DateInterval('P1D'))->format('Y-m-d'),
                        'use-by' => (clone $now)->add(new \DateInterval('P10D'))->format('Y-m-d')
                    ],
                    [
                        'title' => 'rotten_egg',
                        'best-before' => (clone $now)->sub(new \DateInterval('P5D'))->format('Y-m-d'),
                        'use-by' => (clone $now)->sub(new \DateInterval('P1D'))->format('Y-m-d')
                    ]
                ]
            ]
        ];
    }
}
