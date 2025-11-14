<?php

namespace App\Providers;

use App\Interfaces\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\CustomerAssetRepositoryInterface;
use App\Repositories\CustomerAssetRepository;
use App\Interfaces\EstimateRepositoryInterface;
use App\Repositories\EstimateRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CustomerAssetRepositoryInterface::class, CustomerAssetRepository::class);
        $this->app->bind(EstimateRepositoryInterface::class, EstimateRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
