<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Metadata;

/**
 * Extended header interface
 *
 * @package GravityMedia\Metadata\Metadata
 */
interface ExtendedHeaderInterface
{
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
     * Get padding
     *
     * @return int
     */
    public function getPadding();

    /**
     * Get CRC-32
     *
     * @return int
     */
    public function getCrc32();

    /**
     * Get restrictions
     *
     * @return int
     */
    public function getRestrictions();
}
