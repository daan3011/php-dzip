<?php

namespace App\Services;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class DecompressService
{

    private function setFileExtension($extension)
    {
        return "." . $extension;
    }

    private function setDecompressKey($decompressKey)
    {
        return unserialize(file_get_contents($decompressKey));
    }

    private function loadFileIntoString($inFile)
    {
        return file_get_contents($inFile);
    }

    private function setSpaces($fileString) {
        $fileString = preg_replace("/[s]/", " ", $fileString);
        return $fileString;
    }

    private function explodeFileString($fileString) {
        return explode(' ', $fileString);
    }

    private function prepareFile($wordsArray, $fileString)
    {
        foreach ($wordsArray as $word) {
            if (str_starts_with($word, 'n')) {
                // replaces the n's with d's otherwise it will keep on replacing and adds four newlines instead of one
                $fileString = preg_replace("/[n]/", "d ", $fileString);
            }
        }
        return $fileString;
    }

    private function createProgressBar() {
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output);
        $progressBar->setBarCharacter('<fg=green>•</>');
        return $progressBar;
    }

    private function buildDecompressedFile($progressBar, $wordsArray, $deCompressedCompareArray, $withoutExtension, $extension) {
        foreach ($progressBar->iterate($wordsArray) as $word) {
            // in case of n (newline) the intval returns 0 
            if (intval($word) != 0) {
                file_put_contents($withoutExtension . '_decompressed' . $extension, $deCompressedCompareArray[$word] . " ", FILE_APPEND);
            } else {
                file_put_contents($withoutExtension . '_decompressed' . $extension, "\n", FILE_APPEND);
            }
        }
    }

    public function decompress($inFile, $decompressKey, $extension)
    {
        $withoutExtension = pathinfo($inFile, PATHINFO_FILENAME);
        $fileString = $this->loadFileIntoString($inFile);
        $deCompressedCompareArray = $this->setDecompressKey($decompressKey);
        $fileString = $this->setSpaces($fileString);
        $wordsArray = $this->explodeFileString($fileString);
        $fileString = $this->prepareFile($wordsArray, $fileString);
        $wordsArray = $this->explodeFileString($fileString);
        $progressBar = $this->createProgressBar();
        $extension = $this->setFileExtension($extension);
        $this->buildDecompressedFile($progressBar, $wordsArray, $deCompressedCompareArray, $withoutExtension, $extension);
    }
}
