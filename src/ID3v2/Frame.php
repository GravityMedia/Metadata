<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 frame class.
 *
 * @package GravityMedia\Metadata
 */
class Frame
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $dataLength;

    /**
     * @var string
     */
    protected $data;

    /**
     * Create ID3v2 frame object.
     */
    public function __construct()
    {
        $this->dataLength = 0;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
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
     * Get data length.
     *
     * @return int
     */
    public function getDataLength()
    {
        return $this->dataLength;
    }

    /**
     * Set data length.
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
     * Get data.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data.
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
