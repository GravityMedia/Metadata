#Ghostscript
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

###ID3V1###

```php
require 'vendor/autoload.php';

use GravityMedia\MediaTags\SplFileInfo;
use Zend\Stdlib\Hydrator\ClassMethods;

$file = new SplFileInfo('/path/to/input/file.mp3');

$mediaTags = $file->getMediaTags();
$tag = $mediaTags->getId3v1();

var_dump($tag);

$hydrator = new ClassMethods();
$hydrator->hydrate(array(
    'title'   => 'Title'
    'artist'  => 'Artist'
    'album'   => 'Album'
    'year'    => 2014
    'comment' => 'Comment'
    'track'   => 1
), $tag)->save();

var_dump($mediaTags->getId3v1());
```

###ID3V2###

```php
require 'vendor/autoload.php';

use GravityMedia\MediaTags\SplFileInfo;
use Zend\Stdlib\Hydrator\ClassMethods;

$file = new SplFileInfo('/path/to/input/file.mp3');

$mediaTags = $file->getMediaTags();
$tag = $mediaTags->getId3v2();

var_dump($tag);

$hydrator = new ClassMethods();
$hydrator->hydrate(array(
    'title'   => 'Title'
    'artist'  => 'Artist'
    'album'   => 'Album'
    'year'    => 2014
    'comment' => 'Comment'
    'track'   => 1
), $tag)->save();

var_dump($mediaTags->getId3v2());
```
