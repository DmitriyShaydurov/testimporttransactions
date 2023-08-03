<?php

declare(strict_types=1);

namespace App\Services\Importer;

use App\Services\Importer\MappingServiceInterface;

interface ImporterServiceInterface
{
    /**
     * Imports a given report
     *
     * @param  string  $filename Full path to the report
     * @param  string[]  $mapping Report mapping
     *
     * @return Result Result of the import process
     */
    public function process(string $filename, array $mapping): Result;
}
