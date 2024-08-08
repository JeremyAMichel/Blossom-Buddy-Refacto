<?php

namespace App\Interfaces;

interface PlantServiceInterface
{
    public function fetchAndStorePlants(PlantRepositoryInterface $plantRepository): void;

}
