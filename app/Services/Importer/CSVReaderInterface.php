<?php

namespace App\Services\Importer;

use League\Csv\Reader;

interface CSVReaderInterface
{
    public function read(string $filename): Reader;
}
