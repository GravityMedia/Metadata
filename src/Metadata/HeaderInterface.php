<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Metadata;

/**
 * Header interface
 *
 * @package GravityMedia\Metadata\Metadata
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
