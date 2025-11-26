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
use App\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Interfaces\SystemLogRepositoryInterface;
use App\Repositories\SystemLogRepository;
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
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(SystemLogRepositoryInterface::class, SystemLogRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
