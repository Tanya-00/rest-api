<?php

namespace App\Service;

class HealthService
{
    private $health;

    public function __construct($health)
    {
        $this->health = $health;
    }

    public function getHealth()
    {
        return $this->health;
    }
}