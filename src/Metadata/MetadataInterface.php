<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Metadata;

/**
 * Metadata interface.
 *
 * @package GravityMedia\Metadata\Metadata
 */
interface MetadataInterface
{
    /**
     * Returns whether metadata exists.
     *
     * @return bool
     */
    public function exists();

    /**
     * Strip metadata.
     *
     * @return $this
     */
    public function strip();

    /**
     * Read tag.
     *
     * @return null|TagInterface
     */
    public function read();

    /**
     * Write tag.
     *
     * @param TagInterface $tag The tag to write.
     * @param int $version The tag version to write.
     *
     * @return $this
     */
    public function write(TagInterface $tag, $version);
}
