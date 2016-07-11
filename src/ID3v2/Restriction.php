<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 restriction class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Restriction
{
    /**
     * Tag size restriction.
     */
    const RESTRICTION_TAG_SIZE = 0;

    /**
     * Text encoding restriction.
     */
    const RESTRICTION_TEXT_ENCODING = 1;

    /**
     * Text fields size restriction.
     */
    const RESTRICTION_TEXT_FIELDS_SIZE = 2;

    /**
     * Image encoding restriction.
     */
    const RESTRICTION_IMAGE_ENCODING = 3;

    /**
     * Image size restriction.
     */
    const RESTRICTION_IMAGE_SIZE = 4;

    /**
     * Valid values.
     *
     * @var int[]
     */
    protected static $values = [
        self::RESTRICTION_TAG_SIZE,
        self::RESTRICTION_TEXT_ENCODING,
        self::RESTRICTION_TEXT_FIELDS_SIZE,
        self::RESTRICTION_IMAGE_ENCODING,
        self::RESTRICTION_IMAGE_SIZE
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
