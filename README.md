<div align="center">
  <img style="width:500px" src="https://i.imgur.com/I6T4czC.pngta.png" alt="Dzip logo" />

<hr/>



</div>

**Requires PHP 8.0+**

My first version of a simple compression algortihm, Dzip is completely written in vanilla php.
it uses Laravel zero to create the cli but the compress and decompress file are also usable on their own.

I wrote this because I was doing some research into compression algorithms and someone on Stackoverflow said it wasn't possible to write one in php (I will link the post when I find it).

Don't use for compressing important files as the algorithm hasn't been properly tested with the majority of file types, I wrote this purely to prove that it is indeed possible to do in php


# Contents

- [Functionalities](#functionalities)
- [Output formats](#output-formats)
- [How it works](#how-it-works)
- [Commands](#commands)
- [Installation](#installation)
- [Creating standalone application](#creating-standalone-application)
- [Usage](#usage)


## Functionalities

More configuration options will be added in a future update

- Display currently installed version of Dzip and php
- Compress files
- Decompress files
- self update (Comming soon)


## Output formats

- .dip (compressed file)
- .dcc (decompression key)



## How it works

Dzip's compression works by looping over the files contents and checking if the current word is present in an array, if so the word gets replaced with the index of the word followed by an s. Otherwise it will add the word to duplicates and searches for the index of the word and also replaces itself with the index

## Commands

More configuration options will be added in a future update

- Display currently installed version of Dzip and php
- Compress files
- Decompress files
- selfUpdate (Comming soon)

## Installation


#### Install necessary Composer dependencies

```bash
composer install
```

## Creating standalone application

It is possible to build a PHAR archive of dzip and run this as a standalone application (not for windows). the standalone version can be built by doing:

```php
php dzip-cli build:app
```

Then it wil prompt you for a version number, current version is 0.1.0 after this the standalone version will be built. the latest prebuilt version can also be found in the builds folder

## Usage

### Standalone version

The standalone version of dzip-cli can be found inside the builds folder, the current version is 0.1.0

### Non standalone version

The non standalone version of the app is what you get after cloning the repository and installing all of the projects depenendencies

### differences

The standalone version is a .PHAR archive and doesn't provide the option to create custom commands and possibly extend the tool to fit your needs. On Linux and MacOSX this file can be run by navigating into the builds folder (or where ever the standalone version is located on the system) and just calling dzip-cli

### command usage

To compress a file run

```bash
php dzip-cli compress [path/filename]
```

To decompress a file run

```bash
php dzip-cli decompress [path/filename] [decompression_key] [extension]
```