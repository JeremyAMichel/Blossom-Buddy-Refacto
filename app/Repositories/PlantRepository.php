<?php

namespace App\Repositories;

use App\Interfaces\PlantRepositoryInterface;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Collection;

class PlantRepository implements PlantRepositoryInterface
{
    public function getAllPlants(): Collection
    {
        return Plant::all();
    }

    public function getPlantById(int $id)
    {
        return Plant::findOrFail($id);
    }

    public function createPlant(array $data)
    {
        return Plant::create($data);
    }

    public function updatePlant(int $id, array $data)
    {
        $plant = Plant::findOrFail($id);
        $plant->update($data);
        return $plant;
    }

    public function deletePlant(int $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
    }
}