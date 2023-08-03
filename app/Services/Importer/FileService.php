<?php

namespace App\Services\Importer;

use Exception;

class FileService implements FileServiceInterface
{
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     *
     * @return string
     * @throws Exception
     */
    public function getCorrectPath(): string
    {
        if (!file_exists($this->path)) {
            throw new Exception('File not found or is not valid');
        }

        return $this->path;
    }
}
