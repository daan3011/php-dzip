<?php


namespace App\Services;
use PhpParser\Builder\Class_;

class BaseService {
    public function loadFileIntoString($inFile)
    {
        return file_get_contents($inFile);
    }
}