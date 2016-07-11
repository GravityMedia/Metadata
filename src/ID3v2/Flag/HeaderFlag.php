<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Flag;

/**
 * ID3v2 header flag class.
 *
 * @package GravityMedia\Metadata\ID3v2\Flag
 */
class HeaderFlag
{
    /**
     * Unsynchronisation flag
     */
    const FLAG_UNSYNCHRONISATION = 0;

    /**
     * Compression flag
     */
    const FLAG_COMPRESSION = 1;

    /**
     * Extended header flag
     */
    const FLAG_EXTENDED_HEADER = 2;

    /**
     * Experimental indicator flag
     */
    const FLAG_EXPERIMENTAL_INDICATOR = 3;

    /**
     * Footer present flag
     */
    const FLAG_FOOTER_PRESENT = 4;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::FLAG_UNSYNCHRONISATION,
        self::FLAG_COMPRESSION,
        self::FLAG_EXTENDED_HEADER,
        self::FLAG_EXPERIMENTAL_INDICATOR,
        self::FLAG_FOOTER_PRESENT
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
