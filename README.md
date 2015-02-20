#Metadata

Metadata library for PHP

[![Packagist](https://img.shields.io/packagist/v/gravitymedia/metadata.svg)](https://packagist.org/packages/gravitymedia/metadata)
[![Downloads](https://img.shields.io/packagist/dt/gravitymedia/metadata.svg)](https://packagist.org/packages/gravitymedia/metadata)
[![License](https://img.shields.io/packagist/l/gravitymedia/metadata.svg)](https://packagist.org/packages/gravitymedia/metadata)
[![Build](https://img.shields.io/travis/GravityMedia/Metadata.svg)](https://travis-ci.org/GravityMedia/Metadata)
[![Code Quality](https://img.shields.io/scrutinizer/g/GravityMedia/Metadata.svg)](https://scrutinizer-ci.com/g/GravityMedia/Metadata/?branch=master)
[![Coverage](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Metadata.svg)](https://scrutinizer-ci.com/g/GravityMedia/Metadata/?branch=master)
[![PHP Dependencies](https://www.versioneye.com/user/projects/54a6c39d27b014005400004b/badge.svg)](https://www.versioneye.com/user/projects/54a6c39d27b014005400004b)

##Requirements##

This library has the following requirements:

 - PHP 5.4+

##Installation##

Install composer in your project:

```bash
$ curl -s https://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "gravitymedia/metadata": "dev-master"
    }
}
```

Install via composer:

```bash
$ php composer.phar install
```

##Usage##

Currently reading and writing of ID3 (v1 and v2) metadata is supported. The support for more metadata formats will be available soon.

###CLI###

This library contains a useful CLI script. You may export and import the metadata to/from YAML files. More export/import formats will be available soon.

###ID3 v1###

```php
require 'vendor/autoload.php';

use GravityMedia\Metadata\SplFileInfo;

// create new metadata aware file info object
$file = new SplFileInfo('/path/to/input/file.mp3');

// get ID3 v1 metadata
$metadata = $file->getMetadata();
$tag = $metadata->getId3v1Tag();

// dump tag info
var_dump($tag);

// update ID3 v1 metadata
$tag
    ->setTitle('New title')
    ->setArtist('An other artist')
    ->setAlbum('The album title')
    ->setYear(2014)
    ->setComment('This tag was written by metadata library')
    ->setTrack(1)
    ->save();

// dump updated tag info
var_dump($metadata->getId3v1Tag());

// remove ID3 v1 metadata
$tag->remove();
```

###ID3 v2###

```php
require 'vendor/autoload.php';

use GravityMedia\Metadata\SplFileInfo;

// create new metadata aware file info object
$file = new SplFileInfo('/path/to/input/file.mp3');

// get ID3 v2 metadata
$metadata = $file->getMetadata();
$tag = $metadata->getId3v2Tag();

// dump tag info
var_dump($tag);

// update ID3 v2 metadata
$tag
    ->setTitle('New title')
    ->setArtist('An other artist')
    ->setAlbum('The album title')
    ->setYear(2014)
    ->setComment('This tag was written by metadata library')
    ->setTrack(1)
    ->save();

// dump updated tag info
var_dump($metadata->getId3v2Tag());

// remove ID3 v2 metadata
$tag->remove();
```
