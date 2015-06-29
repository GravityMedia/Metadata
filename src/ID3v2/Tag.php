<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\ID3v2\Tag\Header;
use GravityMedia\Metadata\TagInterface;

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
     * @var \ArrayObject
     */
    protected $frames;

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
     * Get frames
     *
     * @return \ArrayObject
     */
    public function getFrames()
    {
        return $this->frames;
    }
}
