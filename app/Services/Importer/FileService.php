<?php

namespace App\Services\Importer;

use Exception;

interface FileServiceInterface
{
    public function load(): string;
}

class FileService implements FileServiceInterface
{
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Load the file from the given path
     *
     * @return string
     * @throws Exception
     */
    public function load(): string
    {
        if (!file_exists($this->path)) {
            throw new Exception('File not found or is not valid');
        }

        return $this->path;
    }
}
