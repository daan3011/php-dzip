<?php
$di = new RecursiveDirectoryIterator('./');
foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
    echo $filename . ' - ' . $file->getSize() . ' bytes <br/>' . PHP_EOL;
}