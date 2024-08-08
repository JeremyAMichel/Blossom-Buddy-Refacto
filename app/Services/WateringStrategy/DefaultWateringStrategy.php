<?php

namespace App\Services\WateringStrategy;

use App\Interfaces\WateringStrategyInterface;
use App\Models\Plant;

class DefaultWateringStrategy implements WateringStrategyInterface
{
    /**
     * Calculate the number of days to pass to the weather service based on the plant watering benchmarks
     * @param Plant $plant
     * @return int
     */
    public function calculateDaysForWeatherService(Plant $plant): array
    {
        $wateringBenchmark = $plant->watering_general_benchmark;
        $unit = $wateringBenchmark['unit'];
        $value = $wateringBenchmark['value'];

        // Calculate the number of days until the next watering
        $daysUntilNextWatering = 0;
        if ($unit === 'days') {
            $range = explode('-', $value);
            $daysUntilNextWatering = (int) $range[0]; // Taking the lower bound of the range
        } elseif ($unit === 'week') {
            $range = explode('-', $value);
            $daysUntilNextWatering = (int) $range[0] * 7; // Convert weeks to days
        }

        // Determine the number of days to pass to the weather service
        return [
            'daysUntilNextWatering' => $daysUntilNextWatering,
            'daysForWeatherService' => $daysUntilNextWatering >= 5 ? 5 : $daysUntilNextWatering
        ];
    }


    /**
     * Calculate the hours until the next watering based on the plant and weather data
     * @param Plant $plant
     * @param array $weatherData
     * @return int The number of hours until the next watering
     */
    public function calculateHoursUntilNextWatering(array $weatherData, int $daysUntilNextWatering): int
    {
        // We will convert days into hours to be able to delay the job
        $hoursUntilNextWatering = $daysUntilNextWatering * 24;

        // For each day in the forecast, calculate a coefficient and apply it to the days until next watering
        foreach ($weatherData as $day) {
            $humidity = $day['avghumidity'];

            // For each 10% above 70%, we add 10% to daysUntilNextWatering
            if ($humidity > 70) {
                $tranchesAbove70 = floor(($humidity - 70) / 10) + 1;
                $hoursUntilNextWatering += $hoursUntilNextWatering * (0.1 * $tranchesAbove70);
            }
            // For each 10% under 40%, we remove 10% to daysUntilNextWatering
            elseif ($humidity < 40) {
                $tranchesBelow40 = floor((40 - $humidity) / 10) - 1;
                $hoursUntilNextWatering -= $hoursUntilNextWatering * (0.1 * $tranchesBelow40);
            }
        }

        return $hoursUntilNextWatering;
    }

    /**
     * Convert hours into days and hours
     * @param int $hours
     * @return array ['days' => int value, 'hours' => int value]
     */
    public function convertHoursToDaysAndHours(int $hours): array
    {
        $days = floor($hours / 24);
        $hours = $hours % 24;

        return [
            'days' => $days,
            'hours' => $hours
        ];
    }
}
