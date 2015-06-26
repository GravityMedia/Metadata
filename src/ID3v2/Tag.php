<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\TagInterface;

/**
 * ID3v2 tag
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Tag implements TagInterface
{
    /**
     * Tag version 2.2
     */
    const VERSION_22 = 0;

    /**
     * Tag version 2.3
     */
    const VERSION_23 = 1;

    /**
     * Tag version 2.4
     */
    const VERSION_24 = 2;

    /**
     * Supported frames
     *
     * @var string[]
     */
    public static $frames = array(
        'TIT2', // title/songname/content description
        'TPE1', // artist(s)/lead performer(s)/soloist(s)
        'TALB', // album/movie/show title
        'TYER', // year
        'COMM', // comments
        'TRCK', // track number/position in set
        'TCON', // genre/content type
        'TPE2', // band/orchestra/accompaniment
        'TIT1', // works/content group description
        'TCOM', // composer(s)
        'TOPE', // original artist(s)/performer(s)
        'TPOS', // disc number/part of a set
        'APIC'  // picture/attached picture
    );

    /**
     * @var string
     */
    protected $version;

    /**
     * Create ID3v1 tag object
     *
     * @param int $version The version (default is 2: v2.4)
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = self::VERSION_24)
    {
        if (!in_array($version, array(self::VERSION_22, self::VERSION_23, self::VERSION_24))) {
            throw new Exception\InvalidArgumentException('Invalid version argument');
        }

        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}
