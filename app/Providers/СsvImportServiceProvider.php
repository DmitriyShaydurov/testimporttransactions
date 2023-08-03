<?php

namespace App\Providers;

use App\Services\Importer\CSVReader;
use App\Services\Importer\CSVReaderInterface;
use App\Services\Importer\DataInserter;
use App\Services\Importer\DataInserterInterface;
use App\Services\Importer\DataProcessor;
use App\Services\Importer\DataProcessorInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\Importer\FileServiceInterface;
use App\Services\Importer\FileService;
use App\Services\Importer\MappingServiceInterface;
use App\Services\Importer\MappingService;
use App\Services\Importer\ImporterServiceInterface;
use App\Services\Importer\ImporterService;

class СsvImportServiceProvider extends ServiceProvider
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

        $this->app->singleton(CSVReaderInterface::class, CSVReader::class);
        $this->app->singleton(DataInserterInterface::class, DataInserter::class);
        $this->app->singleton(DataProcessorInterface::class, DataProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
