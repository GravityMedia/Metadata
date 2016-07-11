<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Filter;

/**
 * Synchsafe integer filter class.
 *
 * @package GravityMedia\Metadata\ID3v2\Filter
 */
class SynchsafeIntegerFilter
{
    /**
     * Encode value.
     *
     * @param int $value
     *
     * @return int
     */
    public function encode($value)
    {
        return ($value & 0x7f) | ($value & 0x3f80) << 1 | ($value & 0x1fc000) << 2 | ($value & 0xfe00000) << 3;
    }

    /**
     * Decode value.
     *
     * @param int $value
     *
     * @return int
     */
    public function decode($value)
    {
        return ($value & 0x7f) | ($value & 0x7f00) >> 1 | ($value & 0x7f0000) >> 2 | ($value & 0x7f000000) >> 3;
    }
}
