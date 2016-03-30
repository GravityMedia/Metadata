<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 tag interface
 *
 * @package GravityMedia\Metadata\ID3v2
 */
interface TagInterface
{
    /**
     * Get version
     *
     * @return int
     */
    public function getVersion();

    /**
     * Get frames
     *
     * @return FrameInterface[]
     */
    public function getFrames();
}
