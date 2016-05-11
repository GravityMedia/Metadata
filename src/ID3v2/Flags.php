<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 flags class.
 *
 * @package GravityMedia\Metadata
 */
class Flags
{
    /**
     * @var array
     */
    protected $flags;

    /**
     * Create ID3v2 flags object.
     *
     * @param array $flags
     */
    public function __construct(array $flags = [])
    {
        $this->flags = $flags;
    }

    /**
     * Whether the flag is enabled.
     *
     * @param int $flag
     *
     * @return bool
     */
    public function isEnabled($flag)
    {
        if (isset($this->flags[$flag])) {
            return $this->flags[$flag];
        }

        return false;
    }
}
