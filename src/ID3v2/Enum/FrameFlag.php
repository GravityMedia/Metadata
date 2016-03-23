<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Enum;

/**
 * ID3v2 frame flag enum
 *
 * @package GravityMedia\Metadata\ID3v2\Enum
 */
class FrameFlag
{
    /**
     * Tag alter preservation flag
     */
    const FLAG_TAG_ALTER_PRESERVATION = 0;

    /**
     * File alter preservation flag
     */
    const FLAG_FILE_ALTER_PRESERVATION = 1;

    /**
     * Read only flag
     */
    const FLAG_READ_ONLY = 2;

    /**
     * Compression flag
     */
    const FLAG_COMPRESSION = 3;

    /**
     * Encryption flag
     */
    const FLAG_ENCRYPTION = 4;

    /**
     * Grouping identity flag
     */
    const FLAG_GROUPING_IDENTITY = 5;

    /**
     * Unsynchronisation
     */
    const FLAG_UNSYNCHRONISATION = 6;

    /**
     * Data length indicator
     */
    const FLAG_DATA_LENGT_INDICATOR = 7;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::FLAG_TAG_ALTER_PRESERVATION,
        self::FLAG_FILE_ALTER_PRESERVATION,
        self::FLAG_READ_ONLY,
        self::FLAG_COMPRESSION,
        self::FLAG_ENCRYPTION,
        self::FLAG_GROUPING_IDENTITY,
        self::FLAG_UNSYNCHRONISATION,
        self::FLAG_DATA_LENGT_INDICATOR
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
