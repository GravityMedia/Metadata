<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags;

/**
 * Media tags aware file info object
 *
 * @package GravityMedia\MediaTags
 */
class SplFileInfo extends \SplFileInfo
{
    /**
     * Get media tags
     *
     * @return \GravityMedia\MediaTags\MediaTags
     */
    public function getMediaTags()
    {
        return new MediaTags($this);
    }
}
