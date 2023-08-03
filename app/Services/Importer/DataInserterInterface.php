<?php

namespace App\Services\Importer;

interface DataInserterInterface
{
    public function insertData(array $merchants, array $batches, array $transactions): void;
}
