<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 version class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Version
{
    /**
     * Tag version 2.2
     */
    const VERSION_22 = 2;

    /**
     * Tag version 2.3
     */
    const VERSION_23 = 3;

    /**
     * Tag version 2.4
     */
    const VERSION_24 = 4;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::VERSION_22,
        self::VERSION_23,
        self::VERSION_24
    ];

    /**
     * Return valid values
     *
     * @return int[]
     */
    public static function values()
    {
        return static::$values;
    }
}
