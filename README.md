#Metadata
[![Project Status](http://stillmaintained.com/GravityMedia/Metadata.png)](http://stillmaintained.com/GravityMedia/Metadata)
[![Build Status](https://travis-ci.org/GravityMedia/Metadata.svg?branch=master)](https://travis-ci.org/GravityMedia/Metadata)

Metadata library for PHP

##Requirements##

This library has the following requirements:

 - PHP 5.4+

##Installation##

Install composer in your project:

```bash
curl -s https://getcomposer.org/installer | php
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
php composer.phar install
```

##Usage##

Currently reading and writing of ID3 (v1 and v2) metadata is supported. The support for more metadata formats will be available soon.

###CLI###

This library contains a useful CLI script. You may export and import the metadata to/from YAML files. More export/import formats will be available soon.

###ID3v1###

```php
require 'vendor/autoload.php';

use GravityMedia\Metadata\SplFileInfo;

// create new metadata aware file info object
$file = new SplFileInfo('/path/to/input/file.mp3');

// get ID3 v1 metadata
$metadata = $file->getMetadata();
$tag = $metadata->getId3v1();

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
var_dump($metadata->getId3v1());

// remove ID3 v1 metadata
$tag->remove();
```

###ID3v2###

```php
require 'vendor/autoload.php';

use GravityMedia\Metadata\SplFileInfo;

// create new metadata aware file info object
$file = new SplFileInfo('/path/to/input/file.mp3');

// get ID3 v2 metadata
$metadata = $file->getMetadata();
$tag = $metadata->getId3v2();

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
var_dump($metadata->getId3v2());

// remove ID3 v2 metadata
$tag->remove();
```
