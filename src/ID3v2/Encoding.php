<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 encoding class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Encoding
{
    /**
     * The ISO-8859-1 encoding.
     */
    const ISO_8859_1 = 0;

    /**
     * The UTF-8 Unicode encoding.
     */
    const UTF_8 = 3;

    /**
     * The UTF-16 Unicode encoding with BOM.
     * */
    const UTF_16 = 1;

    /**
     * The UTF-16BE Unicode encoding without BOM.
     */
    const UTF_16BE = 2;

    /**
     * The UTF-16LE Unicode encoding without BOM.
     * */
    const UTF_16LE = 4;

    /**
     * Valid values.
     *
     * @var int[]
     */
    protected static $values = [
        self::ISO_8859_1,
        self::UTF_16,
        self::UTF_16BE,
        self::UTF_8,
        self::UTF_16LE
    ];

    /**
     * Return valid values.
     *
     * @return int[]
     */
    public static function values()
    {
        return static::$values;
    }
}
