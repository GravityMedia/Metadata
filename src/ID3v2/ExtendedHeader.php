<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 extended header class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class ExtendedHeader
{
    /**
     * @var int
     */
    protected $size;

    /**
     * @var array
     */
    protected $flags;

    /**
     * Create ID3v2 extended header object.
     */
    public function __construct()
    {
        $this->size = 0;
        $this->flags = [];
    }

    /**
     * Get size in bytes.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size in bytes.
     *
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Whether the flag is enabled.
     *
     * @param int $flag
     *
     * @return bool
     */
    public function isFlagEnabled($flag)
    {
        if (isset($this->flags[$flag])) {
            return $this->flags[$flag];
        }

        return false;
    }

    /**
     * Set flags.
     *
     * @param array $flags
     *
     * @return $this
     */
    public function setFlags(array $flags)
    {
        $this->flags = $flags;
        return $this;
    }
}
