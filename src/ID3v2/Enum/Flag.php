<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Enum;

/**
 * Flag enum
 *
 * @package GravityMedia\Metadata\ID3v2\Enum
 */
class Flag
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
     * CRC data present flag
     */
    const FLAG_CRC_DATA_PRESENT = 5;

    /**
     * Tag is an update flag
     */
    const FLAG_TAG_IS_AN_UPDATE = 6;

    /**
     * Tag restrictions flag
     */
    const FLAG_TAG_RESTRICTIONS = 7;

    /**
     * Valid versions
     *
     * @var int[]
     */
    protected static $values = [
        self::FLAG_UNSYNCHRONISATION,
        self::FLAG_COMPRESSION,
        self::FLAG_EXTENDED_HEADER,
        self::FLAG_EXPERIMENTAL_INDICATOR,
        self::FLAG_FOOTER_PRESENT,
        self::FLAG_CRC_DATA_PRESENT,
        self::FLAG_TAG_IS_AN_UPDATE,
        self::FLAG_TAG_RESTRICTIONS
    ];

    /**
     * Return valid versions
     *
     * @return int[]
     */
    public static function values()
    {
        return static::$values;
    }
}
