<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

/**
 * ID3v1 filter class.
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Filter
{
    /**
     * Encode data.
     *
     * @param string $data   The data to encode.
     * @param int    $length The final length.
     *
     * @return string
     */
    public function encode($data, $length)
    {
        return str_pad(substr($data, 0, $length), $length, "\x00", STR_PAD_RIGHT);
    }

    /**
     * Decode data.
     *
     * @param string $data The data to decode.
     *
     * @return string
     */
    public function decode($data)
    {
        return rtrim($data, "\x00");
    }
}
