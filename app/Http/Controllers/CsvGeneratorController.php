<?php

namespace App\Http\Controllers;

use App\Services\Importer\Report;

class CsvGeneratorController extends Controller
{
    public const CHUNK_SIZE = 1000;

    public function generateCsv()
    {
        $start_time = microtime(true);

        // Open a file for writing
        $file = fopen(storage_path('app/test_data.csv'), 'w');

        // Header
        fputcsv($file, [
            Report::MERCHANT_ID,
            Report::MERCHANT_NAME,
            Report::BATCH_DATE,
            Report::BATCH_REF_NUM,
            Report::TRANSACTION_DATE,
            Report::TRANSACTION_TYPE,
            Report::TRANSACTION_CARD_TYPE,
            Report::TRANSACTION_CARD_NUMBER,
            Report::TRANSACTION_AMOUNT,
        ]);

        // Mock data
        $buffer = [];
        for ($i = 0; $i < 1000; $i++) {
            $buffer[] = [
                'mid' . $i,
                'dba' . $i,
                now()->format('Y-m-d'),
                'batch_ref_num' . $i,
                now()->format('Y-m-d'),
                'trans_type' . $i,
                'VI',
                'card_num' . $i,
                rand(-1000, 1000) / 100,
            ];
            // If buffer is full, write it to disk
            if (count($buffer) === self::CHUNK_SIZE) {
                foreach ($buffer as $row) {
                    fputcsv($file, $row);
                }
                // Clear the buffer
                $buffer = [];
            }
        }
        // Write remaining lines in buffer to disk
        foreach ($buffer as $row) {
            fputcsv($file, $row);
        }

        // Close the file
        fclose($file);


        $end_time = microtime(true);

        $execution_time_seconds = $end_time - $start_time;

        return response()->json(['message' => "CSV file has been generated and saved to disk. execution_time_seconds = " . number_format($execution_time_seconds, 9)]);
    }

}
