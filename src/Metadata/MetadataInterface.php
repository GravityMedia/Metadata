<?php
/**
 * This file is part of the Metadata package.
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
     * Returns whether ID3 metadata exists.
     *
     * @return bool
     */
    public function exists();

    /**
     * Strip ID3 metadata.
     *
     * @return $this
     */
    public function strip();

    /**
     * Read ID3 tag.
     *
     * @return null|TagInterface
     */
    public function read();

    /**
     * Write ID3 metadata tag.
     *
     * @param TagInterface $tag The ID3 tag to write.
     *
     * @return $this
     */
    public function write(TagInterface $tag);
}
