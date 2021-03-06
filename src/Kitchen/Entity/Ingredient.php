<?php

namespace Kitchen\Entity;

use Kitchen\Model\IngredientStatus;

class Ingredient
{
    private $title;
    private $bestBefore;
    private $useBy;

    public function __construct($title, $bestBefore, $useBy)
    {
        $this->title = $title;
        $this->bestBefore = new \DateTime($bestBefore);
        $this->useBy = new \DateTime($useBy);
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'best-before' => $this->bestBefore->format('Y-m-d'),
            'use-by' => $this->useBy->format('Y-m-d')
        ];
    }

    public function getStorageStatus()
    {
        $now = new \DateTime();

        if ($now < $this->bestBefore) {
            return IngredientStatus::FRESH;
        } else if ($this->bestBefore <= $now && $now < $this->useBy) {
            return IngredientStatus::NOT_FRESH;
        }

        return IngredientStatus::OVERDUE;
    }
}
