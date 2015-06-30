<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Metadata;

use GravityMedia\Metadata\Exception;

/**
 * Metadata interface
 *
 * @package GravityMedia\Metadata
 */
interface MetadataInterface
{
    /**
     * Returns whether metadata exists
     *
     * @return bool
     */
    public function exists();

    /**
     * Strip metadata
     *
     * @return $this
     */
    public function strip();

    /**
     * Read metadata tag
     *
     * @return TagInterface|null
     */
    public function read();

    /**
     * Write metadata tag
     *
     * @param TagInterface $tag The metadata tag to write
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid tag arguments
     *
     * @return $this
     */
    public function write(TagInterface $tag);
}
