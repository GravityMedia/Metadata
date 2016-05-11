<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Filter;

/**
 * Compression filter class.
 *
 * @package GravityMedia\Metadata\ID3v2\Filter
 */
class CompressionFilter
{
    /**
     * Encode data.
     *
     * @param string $data
     *
     * @return string
     */
    public function encode($data)
    {
        return gzcompress($data);
    }

    /**
     * Decode data.
     *
     * @param string $data
     *
     * @return string
     */
    public function decode($data)
    {
        return gzuncompress($data);
    }
}
