<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface PlantRepositoryInterface
{
    public function getAllPlants(): Collection;

    public function getPlantById(int $id);

    public function createPlant(array $data);

    public function updatePlant(int $id, array $data);

    public function deletePlant(int $id);
}