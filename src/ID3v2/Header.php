<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\InvalidArgumentException;

/**
 * ID3v2 header class.
 *
 * @package GravityMedia\Metadata
 */
class Header
{
    /**
     * @var int
     */
    protected $version;

    /**
     * @var int
     */
    protected $revision;

    /**
     * @var array
     */
    protected $flags;

    /**
     * @var int
     */
    protected $size;

    /**
     * Create ID3v2 header object.
     *
     * @param int $version The version (default is 3: v2.3)
     *
     * @throws InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = Version::VERSION_23)
    {
        if (!in_array($version, Version::values())) {
            throw new InvalidArgumentException('Invalid version.');
        }

        $this->version = $version;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get revision.
     *
     * @return int
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set revision.
     *
     * @param int $revision
     *
     * @return $this
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
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
}
