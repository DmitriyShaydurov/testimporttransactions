<?php

namespace App\Http\Controllers;

use App\Services\Importer\FileServiceInterface;
use App\Services\Importer\ImporterServiceInterface;
use App\Services\Importer\MappingServiceInterface;
use Illuminate\Http\JsonResponse;
use Exception;

class ImporterController extends Controller
{
    protected $importerService;
    protected $fileService;
    protected $mappingService;

    public function __construct(ImporterServiceInterface $importerService, FileServiceInterface $fileService, MappingServiceInterface $mappingService)
    {
        $this->importerService = $importerService;
        $this->fileService = $fileService;
        $this->mappingService = $mappingService;
    }

    /**
     * Import data from CSV file
     *
     * @return JsonResponse
     */
    public function import(): JsonResponse
    {
        // try {
        $mapping =$this->mappingService->generateMapping();
        $path = $this->fileService->getCorrectPath();

        // Process the CSV file
        $result = $this->importerService->process($path, $mapping);

        return response()->json([
            'message' => 'Import completed successfully',
            'merchants' => $result->getMerchantCount(),
            'batches' => $result->getBatchCount(),
            'transactions' => $result->getTransactionCount(),
        ], 200);
        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 400);
        // }
    }
}
