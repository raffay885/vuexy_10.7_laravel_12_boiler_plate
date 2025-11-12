<?php

namespace App\Providers;

use App\Http\Interfaces\RoleRepositoryInterface;
use App\Http\Repositories\RoleRepository;
use App\Http\Interfaces\UserRepositoryInterface;
use App\Http\Repositories\UserRepository;
use App\Http\Interfaces\CustomerAssetRepositoryInterface;
use App\Http\Repositories\CustomerAssetRepository;
use App\Http\Interfaces\EstimateRepositoryInterface;
use App\Http\Repositories\EstimateRepository;
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
