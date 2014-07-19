#MediaTags
[![Project Status](http://stillmaintained.com/GravityMedia/MediaTags.png)](http://stillmaintained.com/GravityMedia/MediaTags)
[![Build Status](https://travis-ci.org/GravityMedia/MediaTags.svg?branch=master)](https://travis-ci.org/GravityMedia/MediaTags)

Media tags library for PHP

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
        "gravitymedia/media-tags": "dev-master"
    }
}
```

Install via composer:

```bash
php composer.phar install
```

##Usage##

Currently reading and writing of ID3 (V1 and V2) tag is supported. The support for more tag formats will be available soon.

###CLI###

This library contains a useful CLI script. You may export and import the tag to/from YAML files. More export/import formats will be available soon.

###ID3V1###

```php
require 'vendor/autoload.php';

use GravityMedia\MediaTags\SplFileInfo;

// create new media tags aware file info object
$file = new SplFileInfo('/path/to/input/file.mp3');

// get ID3 V1 media tag
$mediaTags = $file->getMediaTags();
$tag = $mediaTags->getId3v1();

// dump tag info
var_dump($tag);

// update ID3 V1 media tag
$tag
    ->setTitle('New title')
    ->setArtist('An other artist')
    ->setAlbum('The album title')
    ->setYear(2014)
    ->setComment('This tag was written by media tags library')
    ->setTrack(1)
    ->save();

// dump updated tag info
var_dump($mediaTags->getId3v1());

// remove ID3 V1 media tag
$tag->remove();
```

###ID3V2###

```php
require 'vendor/autoload.php';

use GravityMedia\MediaTags\SplFileInfo;

// create new media tags aware file info object
$file = new SplFileInfo('/path/to/input/file.mp3');

// get ID3 V2 media tag
$mediaTags = $file->getMediaTags();
$tag = $mediaTags->getId3v2();

// dump tag info
var_dump($tag);

// update ID3 V2 media tag
$tag
    ->setTitle('New title')
    ->setArtist('An other artist')
    ->setAlbum('The album title')
    ->setYear(2014)
    ->setComment('This tag was written by media tags library')
    ->setTrack(1)
    ->save();

// dump updated tag info
var_dump($mediaTags->getId3v2());

// remove ID3 V2 media tag
$tag->remove();
```
