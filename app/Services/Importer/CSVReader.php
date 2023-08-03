<?php

namespace App\Services\Importer;

use League\Csv\Reader;

class CSVReader implements CSVReaderInterface
{
    /**
     * Сreates an object for reading a CSV file, and during the subsequent iteration,
     * each row of the CSV is read one by one. This allows processing files that exceed
     * the available memory, as only a small fragment of the file is held in memory at any given time.
     *
     * @param string $filename The path to the CSV file
     * @return Reader The CSV reader instance
     */
    public function read(string $filename): Reader
    {
        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0); // Set the CSV header offset
        return $csv;
    }
}
