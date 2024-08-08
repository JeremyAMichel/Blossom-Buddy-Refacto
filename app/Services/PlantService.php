<?php

namespace App\Services;

use App\Interfaces\PlantRepositoryInterface;
use App\Interfaces\PlantServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// Service appelé dans la commande FetchPlants
class PlantService implements PlantServiceInterface
{
    protected $apiUrl = 'https://perenual.com/api/species/details';

    /**
     * Fetches plant data from the Perenual API and stores it in the database.
     * @param PlantRepositoryInterface $plantRepository
     * @return void
     */
    public function fetchAndStorePlants(PlantRepositoryInterface $plantRepository): void
    {
        for ($id = 1; $id <= 50; $id++) {
            $plantData = $this->fetchPlantData($id);

            if ($plantData && !empty($plantData)) {
                $plantDataFiltered = $this->filterPlantData($plantData);
                $this->storePlantData($plantDataFiltered, $plantRepository);
            }
        }
    }

    /**
     * Fetches plant data from the Perenual API.
     * @param int $id
     * @return array
     */
    private function fetchPlantData(int $id): array
    {
        $apiKey = env('API_PERENUAL_KEY');

        $response = Http::get("{$this->apiUrl}/{$id}", [
            'key' => $apiKey
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            Log::error("Failed to fetch plant with ID {$id}: " . $response->body());
            return [];
        }
    }

    /**
     * Filters plant data to keep only the relevant fields.
     * @param array $plantData
     * @return array
     */
    private function filterPlantData(array $plantData): array
    {
        return [
            'api_id' => $plantData['id'],
            'common_name' => $plantData['common_name'],
            'watering_general_benchmark' => $plantData['watering_general_benchmark'],
            'watering' => $plantData['watering'],
            'watering_period' => $plantData['watering_period'],
            'flowers' => $plantData['flowers'],
            'fruits' => $plantData['fruits'],
            'leaf' => $plantData['leaf'],
            'growth_rate' => $plantData['growth_rate'],
            'maintenance' => $plantData['maintenance'],
        ];
    }

    /**
     * Stores plant data in the database.
     * @param array $plantData
     * @param PlantRepositoryInterface $plantRepository
     * @return void
     */
    private function storePlantData(array $plantData, PlantRepositoryInterface $plantRepository): void
    {
        $plantRepository->updateOrCreatePlant(['api_id' => $plantData['api_id']], $plantData);
    }
}
