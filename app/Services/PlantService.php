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

    public function fetchAndStorePlants(PlantRepositoryInterface $plantRepository): void
    {
        $apiKey = env('API_PERENUAL_KEY');

        for ($id = 1; $id <= 50; $id++) {
            $response = Http::get("{$this->apiUrl}/{$id}", [
                'key' => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Filtrer les données nécessaires
                $plantData = [
                    'api_id' => $data['id'],
                    'common_name' => $data['common_name'],
                    'watering_general_benchmark' => $data['watering_general_benchmark'],
                    'watering' => $data['watering'],
                    'watering_period' => $data['watering_period'],
                    'flowers' => $data['flowers'],
                    'fruits' => $data['fruits'],
                    'leaf' => $data['leaf'],
                    'growth_rate' => $data['growth_rate'],
                    'maintenance' => $data['maintenance'],
                ];

                $plantRepository->updateOrCreatePlant(['api_id' => $data['id']], $plantData);
            } else {
                Log::error("Failed to fetch plant with ID {$id}: " . $response->body());
            }
        }
    }
}