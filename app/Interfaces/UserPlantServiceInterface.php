<?php

namespace App\Interfaces;

use App\Models\Plant;
use App\Models\User;

interface UserPlantServiceInterface
{
    public function addPlantToUser(User $user, string $city, Plant $plant, WeatherServiceInterface $weatherService, WateringStrategyInterface $wateringStrategy): array;   
}