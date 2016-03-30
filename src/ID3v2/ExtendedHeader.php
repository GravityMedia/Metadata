<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\ID3v2\ExtendedHeaderInterface;

/**
 * ID3v2 extended header
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class ExtendedHeader implements ExtendedHeaderInterface
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
     * @var int
     */
    protected $padding;

    /**
     * @var int
     */
    protected $crc32;

    /**
     * @var int
     */
    protected $restrictions;

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
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * Set padding
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
     * {@inheritdoc}
     */
    public function getCrc32()
    {
        return $this->crc32;
    }

    /**
     * Set CRC-32
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
     * {@inheritdoc}
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * Set restrictions
     *
     * @param int $restrictions
     *
     * @return $this
     */
    public function setRestrictions($restrictions)
    {
        $this->restrictions = $restrictions;
        return $this;
    }
}
