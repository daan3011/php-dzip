<?php

namespace App\Services;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CompressService extends Command
{
    public function compress($inFile)
    {
        $compressedWords = [];
        $key = 1;
        $duplicates = 0;
        $withoutExtension = pathinfo($inFile, PATHINFO_FILENAME);

        // load file contents into string
        $fileString = file_get_contents($inFile);

        // replace all newlines with "n "
        $fileString = preg_replace("/[\n]/", "n ", $fileString);

        // split string into array on spaces and tabs
        $wordsArray = preg_split('/[\s]+/', $fileString);

        // loop checking if a word is already present in the compressedWords array and otherwise adds it
        // if the word is already present 1 gets added to duplicates
        foreach ($wordsArray as $word) {
            if (!in_array($word, $compressedWords)) {
                global $compressedWords;
                $compressedWords[$key] = $word;
                $key++;
            } else {
                $duplicates += 1;
            }
        }

        //generates decompression keyfile and writes the serialized version of the compressedWords array to it
        file_put_contents($withoutExtension . "_decompress_key.ddc", serialize($compressedWords));

        // load file contents into string
        $fileString = file_get_contents($inFile);

        // replace all newlines with "n "
        $fileString = preg_replace("/[\n]/", "n ", $fileString);

        // split string into array on spaces and tabs
        $compareArray = preg_split('/[\s]+/', $fileString);

        // creating cli progressbar
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output);
        $progressBar->setBarCharacter('<fg=green>•</>');

        // loop checking if the current word is in the compressed word array, if so it returns its corresponding key
        foreach ($progressBar->iterate($compareArray) as $compareWord) {
            // returns the key
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
        echo "Removed $duplicates duplicates" . PHP_EOL;
    }
}
