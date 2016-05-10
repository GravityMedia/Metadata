<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

/**
 * ID3v1 version class.
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Version
{
    /**
     * Tag version 1.0
     */
    const VERSION_10 = 0;

    /**
     * Tag version 1.1
     */
    const VERSION_11 = 1;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::VERSION_10,
        self::VERSION_11
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
