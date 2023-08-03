<?php

namespace App\Services\Importer;

interface DataProcessorInterface
{
    public function processRecord(array $record, array $mapping, &$merchants, &$batches, &$transactions): void;
}
