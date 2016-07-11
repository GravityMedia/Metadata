<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Filter;

use GravityMedia\Metadata\ID3v2\Encoding;

/**
 * Charset filter class.
 *
 * @package GravityMedia\Metadata\ID3v2\Filter
 */
class CharsetFilter
{
    /**
     * String representations of encodings.
     *
     * @var string[]
     */
    protected static $encodings = [
        Encoding::ISO_8859_1 => 'ISO-8859-1',
        Encoding::UTF_8 => 'UTF-8',
        Encoding::UTF_16 => 'UTF-16',
        Encoding::UTF_16BE => 'UTF-16BE',
        Encoding::UTF_16LE => 'UTF-16LE'
    ];

    /**
     * The internal encoding.
     *
     * @var int
     */
    private $encoding;

    /**
     * Create charset filter object.
     *
     * @param int $encoding
     */
    public function __construct($encoding = Encoding::UTF_8)
    {
        $this->encoding = $encoding;
    }

    /**
     * Encode string.
     *
     * @param string $string
     * @param int    $encoding
     *
     * @return string
     */
    public function encode($string, $encoding = Encoding::UTF_8)
    {
        if ($encoding === $this->encoding) {
            return $string;
        }

        return iconv(static::$encodings[$this->encoding], static::$encodings[$encoding], $string);
    }

    /**
     * Decode string.
     *
     * @param string $string
     * @param int    $encoding
     *
     * @return string
     */
    public function decode($string, $encoding = Encoding::UTF_8)
    {
        if ($encoding === $this->encoding) {
            return $string;
        }

        return iconv(static::$encodings[$encoding], static::$encodings[$this->encoding], $string);
    }
}
