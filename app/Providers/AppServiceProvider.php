<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Importer\FileServiceInterface;
use App\Services\Importer\FileService;
use App\Services\Importer\MappingServiceInterface;
use App\Services\Importer\MappingService;
use App\Services\Importer\ImporterServiceInterface;
use App\Services\Importer\ImporterService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $path = storage_path('app/test_data.csv');
        $this->app->singleton(FileServiceInterface::class, function () use ($path) {
            return new FileService($path);
        });

        $this->app->singleton(MappingServiceInterface::class, MappingService::class);
        $this->app->singleton(ImporterServiceInterface::class, ImporterService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
