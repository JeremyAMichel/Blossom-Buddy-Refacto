<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface PlantRepositoryInterface
{
    public function getAllPlants(): Collection;

    public function getPlantById(int $id);

    public function getPlantLikeCommonName(string $commonName);

    public function createPlant(array $data);

    public function updatePlant(int $id, array $data);
   
    public function updateOrCreatePlant(array $api_id, array $plantData);

    public function deletePlant(int $id);
}