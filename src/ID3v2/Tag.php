<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Metadata\TagInterface;

/**
 * ID3v2 tag
 *
 * @package GravityMedia\Metadata
 */
class Tag implements TagInterface
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
     * @var \ArrayObject
     */
    protected $frames;

    /**
     * @var Footer
     */
    protected $footer;

    /**
     * Create ID3v2 tag object
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
     * Set extended header
     *
     * @param ExtendedHeader $extendedHeader
     *
     * @return $this
     */
    public function setExtendedHeader(ExtendedHeader $extendedHeader)
    {
        $this->extendedHeader = $extendedHeader;
        return $this;
    }

    /**
     * Get extendedHeader
     *
     * @return ExtendedHeader
     */
    public function getExtendedHeader()
    {
        return $this->extendedHeader;
    }

    /**
     * Get frames
     *
     * @return \ArrayObject
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * Set footer
     *
     * @param Footer $footer
     *
     * @return $this
     */
    public function setFooter(Footer $footer)
    {
        $this->footer = $footer;
        return $this;
    }

    /**
     * Get footer
     *
     * @return Footer
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        // ToDo
    }
}
