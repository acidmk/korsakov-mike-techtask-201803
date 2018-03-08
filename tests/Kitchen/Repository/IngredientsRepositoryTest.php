<?php

namespace Kitchen\Tests;

use PHPUnit\Framework\TestCase;
use Kitchen\Repository\IngredientRepository;
use Kitchen\Model\IngredientStatus;

class IngredientsRepositoryTest extends TestCase
{
    private $repo;
    private $ingredients;

    public function setUp()
    {
        $now = new \DateTime();

        $this->ingredients = [
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

        $this->repo = new IngredientRepository();
        $this->repo->loadIngredients($this->ingredients);
    }

    public function testFindIngredientByKey()
    {
        $ingredient = $this->repo->findIngredientByKey('fresh_ham');
        $this->assertEquals($ingredient->toArray(), $this->ingredients[0]);
    }

    public function testIngredientStatus()
    {
        $ingredient = $this->repo->findIngredientByKey('fresh_ham');
        $this->assertEquals($ingredient->getStorageStatus(), IngredientStatus::FRESH);

        $ingredient = $this->repo->findIngredientByKey('dry_cheese');
        $this->assertEquals($ingredient->getStorageStatus(), IngredientStatus::NOT_FRESH);

        $ingredient = $this->repo->findIngredientByKey('rotten_egg');
        $this->assertEquals($ingredient->getStorageStatus(), IngredientStatus::OVERDUE);
    }
}
