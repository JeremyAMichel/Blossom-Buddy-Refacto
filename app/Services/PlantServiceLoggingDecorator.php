<?php

namespace App\Services;


use App\Interfaces\LoggingServiceInterface;
use App\Interfaces\PlantRepositoryInterface;
use App\Interfaces\PlantServiceInterface;

class PlantServiceLoggingDecorator implements PlantServiceInterface
{
    private PlantService $plantService;
    private LoggingServiceInterface $loggingService;

    public function __construct(PlantService $plantService, LoggingService $loggingService)
    {
        $this->plantService = $plantService;
        $this->loggingService = $loggingService;
    }

    public function fetchAndStorePlants(PlantRepositoryInterface $plantRepository): void
    {
        $this->loggingService->log('Début du fetch des plantes.');
        $this->plantService->fetchAndStorePlants($plantRepository);
        $this->loggingService->log('Fin du fetch des plantes.');
    }
}