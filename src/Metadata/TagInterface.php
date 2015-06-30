<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Metadata;

/**
 * Tag interface
 *
 * @package GravityMedia\Metadata
 */
interface TagInterface
{
    /**
     * Render the tag as a binary vector, suitable to be written to disk
     *
     * @return string
     */
    public function render();
}
