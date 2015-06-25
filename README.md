# Metadata

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gravitymedia/metadata.svg)](https://packagist.org/packages/gravitymedia/metadata)
[![Software License](https://img.shields.io/packagist/l/gravitymedia/metadata.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/GravityMedia/Metadata.svg)](https://travis-ci.org/GravityMedia/Metadata)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Metadata.svg)](https://scrutinizer-ci.com/g/GravityMedia/Metadata/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/GravityMedia/Metadata.svg)](https://scrutinizer-ci.com/g/GravityMedia/Metadata)
[![Total Downloads](https://img.shields.io/packagist/dt/gravitymedia/metadata.svg)](https://packagist.org/packages/gravitymedia/metadata)
[![Dependency Status](https://img.shields.io/versioneye/d/php/gravitymedia:metadata.svg)](https://www.versioneye.com/user/projects/54a6c39d27b014005400004b)

Metadata library for PHP

## Requirements

This library has the following requirements:

- PHP 5.4+ or HHVM

## Installation

Install composer in your project:

``` bash
$ curl -s https://getcomposer.org/installer | php
```

Require the package via Composer:

``` bash
$ php composer.phar require gravitymedia/metadata
```

## Usage

Currently reading and writing of ID3 (v1 and v2) metadata is supported. The support for more metadata formats will be
available soon.

### ID3 v1

``` php
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

### ID3 v2

``` php
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

## Testing

``` bash
$ php composer.phar test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Daniel Schröder](https://github.com/pCoLaSD)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
