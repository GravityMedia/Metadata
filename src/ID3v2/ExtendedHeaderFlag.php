<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 extended header flag class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class ExtendedHeaderFlag
{
    /**
     * Tag is an update flag
     */
    const FLAG_TAG_IS_AN_UPDATE = 0;

    /**
     * CRC data present flag
     */
    const FLAG_CRC_DATA_PRESENT = 1;

    /**
     * Tag restrictions flag
     */
    const FLAG_TAG_RESTRICTIONS = 2;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::FLAG_TAG_IS_AN_UPDATE,
        self::FLAG_CRC_DATA_PRESENT,
        self::FLAG_TAG_RESTRICTIONS
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
