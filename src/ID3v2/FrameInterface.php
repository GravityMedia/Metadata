<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * Frame interface
 *
 * @package GravityMedia\Metadata\ID3v2
 */
interface FrameInterface
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get size in bytes
     *
     * @return int
     */
    public function getSize();

    /**
     * Whether the flag is enabled
     *
     * @param int $flag
     *
     * @return bool
     */
    public function isFlagEnabled($flag);

    /**
     * Get data length
     *
     * @return int
     */
    public function getDataLength();

    /**
     * Get data
     *
     * @return string
     */
    public function getData();
}
