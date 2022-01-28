<?php

namespace App\Services;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CompressService extends Command
{

    private $key = 1;
    private $duplicates = 0;

    private function removeFileExtention($inFile)
    {
        return pathinfo($inFile, PATHINFO_FILENAME);
    }

    private function loadFileIntoString($inFile)
    {
        return file_get_contents($inFile);
    }

    private function prepareFile($fileString)
    {
        $fileString = preg_replace("/[\n]/", "n ", $fileString);
        return preg_split('/[\s]+/', $fileString);
    }

    private function createWordsArray($wordsArray, $compressedWords)
    {
        foreach ($wordsArray as $word) {
            if (!in_array($word, $compressedWords)) {
                $compressedWords[$this->key] = $word;
                $this->key++;
            } else {
                $this->duplicates += 1;
            }
        }
        return $compressedWords;
    }

    private function createDecompressionKey($withoutExtension, $compressedWords)
    {
        file_put_contents($withoutExtension . "_decompress_key.ddc", serialize($compressedWords));
    }

    private function createProgressBar() {
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output);
        $progressBar->setBarCharacter('<fg=green>•</>');
        return $progressBar;
    }

    private function buildCompressedFile($progressBar, $wordsArray, $compressedWords, $withoutExtension) {
        foreach ($progressBar->iterate($wordsArray) as $compareWord) {
            $search = array_search($compareWord, $compressedWords);
            if ($search) {
                //if the current word equals "n" append "n" to compressed file
                if ($compressedWords[$search] == "n") {
                    file_put_contents($withoutExtension . '.dzip', "n", FILE_APPEND);
                } else {
                    // appends the current word's corresponding key to compressed file + "s" for spaces
                    file_put_contents($withoutExtension . '.dzip', $search . "s", FILE_APPEND);
                }
            }
        }
    }

    public function compress($inFile)
    {
        $compressedWords = [];

        $withoutExtension = $this->removeFileExtention($inFile);

        $fileString = $this->loadFileIntoString($inFile);

        $wordsArray = $this->prepareFile($fileString);

        $compressedWords = $this->createWordsArray($wordsArray, $compressedWords);

        $this->createDecompressionKey($withoutExtension, $compressedWords);

        $progressBar = $this->createProgressBar();

        $this->buildCompressedFile($progressBar, $wordsArray, $compressedWords, $withoutExtension);
        
        echo "Removed $this->duplicates duplicates" . PHP_EOL;
    }
}