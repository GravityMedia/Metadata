<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\InvalidArgumentException;

/**
 * ID3v2 tag class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Tag
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
     * @var int
     */
    protected $padding;

    /**
     * @var int
     */
    protected $crc32;

    /**
     * @var int[]
     */
    protected $restrictions;

    /**
     * @var Frame[]
     */
    protected $frames;

    /**
     * Create ID3v2 tag object.
     *
     * @param int $version The version (default is 3: v2.3)
     *
     * @throws InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = Version::VERSION_23)
    {
        if (!in_array($version, Version::values(), true)) {
            throw new InvalidArgumentException('Invalid version.');
        }

        $this->version = $version;
        $this->revision = 0;
        $this->padding = 0;
        $this->crc32 = 0;
        $this->restrictions = [];
        $this->frames = [];
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
     * Get padding.
     *
     * @return int
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * Set padding.
     *
     * @param int $padding
     *
     * @return $this
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;

        return $this;
    }

    /**
     * Get CRC-32.
     *
     * @return int
     */
    public function getCrc32()
    {
        return $this->crc32;
    }

    /**
     * Set CRC-32.
     *
     * @param int $crc32
     *
     * @return $this
     */
    public function setCrc32($crc32)
    {
        $this->crc32 = $crc32;

        return $this;
    }

    /**
     * Get restriction.
     *
     * @param int $restriction
     *
     * @return int
     */
    public function getRestriction($restriction)
    {
        if (isset($this->restrictions[$restriction])) {
            return $this->restrictions[$restriction];
        }

        return -1;
    }

    /**
     * Set restrictions
     *
     * @param int[] $restrictions
     *
     * @return $this
     */
    public function setRestrictions(array $restrictions)
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    /**
     * Get frames
     *
     * @return Frame[]
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * Add frame
     *
     * @param Frame $frame
     *
     * @return $this
     */
    public function addFrame(Frame $frame)
    {
        $this->frames[] = $frame;

        return $this;
    }
}
