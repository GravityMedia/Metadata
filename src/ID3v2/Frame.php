<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\ID3v2\FrameInterface;

/**
 * ID3v2 frame
 *
 * @package GravityMedia\Metadata
 */
class Frame implements FrameInterface
{
    /**
     * @var string
     */
    protected $name;

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
    protected $dataLength;

    /**
     * @var string
     */
    protected $data;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getDataLength()
    {
        return $this->dataLength;
    }

    /**
     * Set data length
     *
     * @param int $dataLength
     *
     * @return $this
     */
    public function setDataLength($dataLength)
    {
        $this->dataLength = $dataLength;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set $data
     *
     * @param string $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
