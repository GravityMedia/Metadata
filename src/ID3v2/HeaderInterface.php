<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * Header interface
 *
 * @package GravityMedia\Metadata\ID3v2
 */
interface HeaderInterface
{
    /**
     * Get version
     *
     * @return int
     */
    public function getVersion();

    /**
     * Get revision
     *
     * @return int
     */
    public function getRevision();

    /**
     * Whether the flag is enabled
     *
     * @param int $flag
     *
     * @return bool
     */
    public function isFlagEnabled($flag);

    /**
     * Get size in bytes
     *
     * @return int
     */
    public function getSize();
}
