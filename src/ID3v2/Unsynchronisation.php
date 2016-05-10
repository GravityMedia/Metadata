<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * Unsynchronisation class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Unsynchronisation
{
    /**
     * Encode unsynchronisation.
     *
     * @param string $data
     *
     * @return string
     */
    public static function encode($data)
    {
        return preg_replace('/\xff(?=[\xe0-\xff])/', "\xff\x00", preg_replace('/\xff\x00/', "\xff\x00\x00", $data));
    }

    /**
     * Decode unsynchronisation.
     *
     * @param string $data
     *
     * @return string
     */
    public static function decode($data)
    {
        return preg_replace('/\xff\x00\x00/', "\xff\x00", preg_replace('/\xff\x00(?=[\xe0-\xff])/', "\xff", $data));
    }
}
