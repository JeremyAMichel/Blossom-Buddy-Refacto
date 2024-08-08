<?php

namespace App\Services;

use App\Interfaces\UserPlantServiceInterface;
use App\Interfaces\WateringStrategyInterface;
use App\Interfaces\WeatherServiceInterface;
use App\Jobs\SendWateringReminder;
use App\Models\Plant;
use App\Models\User;

class UserPlantService implements UserPlantServiceInterface
{
    /**
     * Add a plant to the user's list and schedule a reminder
     * @param User $user
     * @param string $city
     * @param Plant $plant
     * @param WeatherServiceInterface $weatherService
     * @param WateringStrategyInterface $wateringStrategy
     * @return array The number of days and hours until the next watering
     */
    public function addPlantToUser(User $user, string $city, Plant $plant, WeatherServiceInterface $weatherService, WateringStrategyInterface $wateringStrategy): array
    {
        $resultDays = $wateringStrategy->calculateDaysForWeatherService($plant);
        
        // Use the weather service to get the forecast for the city
        $weatherData = $weatherService->getWeatherForecast($city, $resultDays['daysForWeatherService']);

        $hoursUntilNextWatering = $wateringStrategy->calculateHoursUntilNextWatering($weatherData, $resultDays['daysUntilNextWatering']);

        // Convert hours into days + hours for the delay
        $daysAndHoursUntilNextWatering = $wateringStrategy->convertHoursToDaysAndHours($hoursUntilNextWatering);
        
        $user->plants()->attach($plant->id, ['city' => $city]);

        // Dispatch the Laravel job to send the reminder
        SendWateringReminder::dispatch($user, $plant->common_name)->delay(now()->addDays($daysAndHoursUntilNextWatering['days'])->addHours($daysAndHoursUntilNextWatering['hours']));

        return $daysAndHoursUntilNextWatering;

    }
}