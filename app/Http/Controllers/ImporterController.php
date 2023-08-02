<?php

namespace App\Http\Controllers;

use App\Services\Importer\ImporterServiceInterface;
use Illuminate\Http\JsonResponse;
use Exception;

class ImporterController extends Controller
{
    protected $importerService;

    public function __construct(ImporterServiceInterface $importerService)
    {
        $this->importerService = $importerService;
    }

    /**
     * Import data from CSV file
     *
     * @return JsonResponse
     */
    public function import(): JsonResponse
    {
        try {
            $path = $this->importerService->getFileService()->load(); // Load the file
            $mapping = $this->importerService->getMappingService()->generateMapping(); // Generate the mapping

            // Process the CSV file
            $result = $this->importerService->process($path, $mapping);

            return response()->json([
                'message' => 'Import completed successfully',
                'merchants' => $result->getMerchantCount(),
                'batches' => $result->getBatchCount(),
                'transactions' => $result->getTransactionCount(),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
