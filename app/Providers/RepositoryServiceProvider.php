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
use Illuminate\Support\ServiceProvider;
use App\Interfaces\SyncroProductRepositoryInterface;
use App\Repositories\SyncroProductRepository;
use App\Interfaces\EsetProductRepositoryInterface;
use App\Repositories\EsetProductRepository;
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
        $this->app->bind(SyncroProductRepositoryInterface::class, SyncroProductRepository::class);
        $this->app->bind(EsetProductRepositoryInterface::class, EsetProductRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
