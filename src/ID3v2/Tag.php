<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\BadMethodCallException;

/**
 * ID3v2 tag class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Tag
{
    /**
     * @var Header
     */
    protected $header;

    /**
     * @var ExtendedHeader
     */
    protected $extendedHeader;

    /**
     * @var Frame[]
     */
    protected $frames;

    /**
     * Create tag object.
     *
     * @param Header $header
     */
    public function __construct(Header $header)
    {
        $this->header = $header;
        $this->frames = new \ArrayObject();
    }

    /**
     * Get header
     *
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Get extended header
     *
     * @return ExtendedHeader
     */
    public function getExtendedHeader()
    {
        return $this->extendedHeader;
    }

    /**
     * Set extended header
     *
     * @param ExtendedHeader $extendedHeader
     *
     * @throws BadMethodCallException An exception is thrown on ID3 v2.2 tag
     *
     * @return $this
     */
    public function setExtendedHeader(ExtendedHeader $extendedHeader)
    {
        if (!in_array($this->getVersion(), [Version::VERSION_23, Version::VERSION_24])) {
            throw new BadMethodCallException('Extended header is not supported in this version.');
        }

        $this->extendedHeader = $extendedHeader;

        return $this;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->header->getVersion();
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
        $this->frames->append($frame);
        return $this;
    }
}
