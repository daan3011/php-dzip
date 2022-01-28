<?php

namespace App\Services;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class DecompressService
{

    protected $fileExtension = "";
    protected $decompressKey = "";

    public function setFileExtension($extension)
    {
        $this->fileExtension = $extension;
    }

    public function decompressKey($decompressKey)
    {
        $this->decompressKey = $decompressKey;
    }

    public function decompress($inFile)
    {
        $withoutExtension = pathinfo($inFile, PATHINFO_FILENAME);

        // load compressed file into string
        $fileString = file_get_contents($inFile);

        // unserialize decompression keyfile to get the array with indexed words
        $deCompressedCompareArray = unserialize(file_get_contents($this->decompressKey));

        // relace all s's in the compressed file with spaces
        $fileString = preg_replace("/[s]/", " ", $fileString);

        // expode string on the spaces to get an array of array keys for decompression
        $wordsArray = explode(' ', $fileString);

        // some words have a n in fron of them to delimit a newline, this loop checks for that with str_starts_with (php 8.x required)
        foreach ($wordsArray as $word) {
            if (str_starts_with($word, 'n')) {
                // replaces the n's with d's otherwise it will keep on replacing and adds four newlines instead of one
                $fileString = preg_replace("/[n]/", "d ", $fileString);
            }
        }

        // creates array of numbers (array keys)
        $wordsArray = explode(' ', $fileString);

        // creating cli progressbar
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output);
        $progressBar->setBarCharacter('<fg=green>•</>');

        foreach ($progressBar->iterate($wordsArray) as $word) {
            // in case of n (newline) the intval returns 0 
            if (intval($word) != 0) {
                // append word to file, including space
                file_put_contents($withoutExtension . '_decompressed.' . $this->fileExtension, $deCompressedCompareArray[$word] . " ", FILE_APPEND);
            } else {
                // in case of n (newline) the intval returns 0, and a "\n" is appended
                file_put_contents($withoutExtension . '_decompressed.' . $this->fileExtension, "\n", FILE_APPEND);
            }
        }
    }
}
