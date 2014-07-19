<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata;

/**
 * Metadata aware file info object
 *
 * @package GravityMedia\Metadata
 */
class SplFileInfo extends \SplFileInfo
{
    /**
     * Get metadata
     *
     * @return \GravityMedia\Metadata\Metadata
     */
    public function getMetadata()
    {
        return new Metadata($this);
    }
}
