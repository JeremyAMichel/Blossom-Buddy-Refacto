<?php

namespace App\Repositories;

use App\Interfaces\PlantRepositoryInterface;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function getPlantLikeCommonName(string $commonName)
    {
        return Plant::where('common_name', 'LIKE', '%' . $commonName . '%')->firstOrFail();
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
        try {
            $plant = Plant::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        $plant->delete();
    }
}
