<?php

namespace App\Providers;

use App\Http\Controllers\AuthController;
use App\Interfaces\AuthControllerInterface;
use App\Interfaces\PlantRepositoryInterface;
use App\Interfaces\PlantServiceInterface;
use App\Interfaces\UserPlantServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\WateringStrategyInterface;
use App\Interfaces\WeatherServiceInterface;
use App\Repositories\PlantRepository;
use App\Repositories\UserRepository;
use App\Services\PlantService;
use App\Services\UserPlantService;
use App\Services\WateringStrategy\DefaultWateringStrategy;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthControllerInterface::class, AuthController::class);
        $this->app->singleton(WeatherServiceInterface::class, WeatherService::class);
        $this->app->singleton(PlantServiceInterface::class, PlantService::class);
        $this->app->singleton(UserPlantServiceInterface::class, UserPlantService::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(PlantRepositoryInterface::class, PlantRepository::class);
        $this->app->bind(WateringStrategyInterface::class, DefaultWateringStrategy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
