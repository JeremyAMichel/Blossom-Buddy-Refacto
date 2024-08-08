<?php

namespace App\Interfaces;

use App\Models\Plant;
use App\Models\User;

interface WateringStrategyInterface
{
    public function calculateDaysForWeatherService(Plant $plant): array;
    public function calculateHoursUntilNextWatering(array $weatherData, int $daysForWeatherService): int;
    public function convertHoursToDaysAndHours(int $hours): array;
}
