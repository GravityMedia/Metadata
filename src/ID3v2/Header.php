<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\InvalidArgumentException;
use GravityMedia\Metadata\ID3v2\Enum\Version;
use GravityMedia\Metadata\Metadata\HeaderInterface;

/**
 * ID3v2 header
 *
 * @package GravityMedia\Metadata
 */
class Header implements HeaderInterface
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
     * Create header object.
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
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set revision
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
     * {@inheritdoc}
     */
    public function isFlagEnabled($flag)
    {
        if (isset($this->flags[$flag])) {
            return $this->flags[$flag];
        }

        return false;
    }

    /**
     * Set flags
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
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size in bytes
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
