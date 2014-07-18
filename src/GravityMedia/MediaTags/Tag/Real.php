<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Tag;

/**
 * Real tag
 *
 * @package GravityMedia\MediaTags\Tag
 */
class Real extends AbstractTag
{
    /**
     * Save tag
     *
     * @return $this
     */
    public function save()
    {
        $data = array(
            'title' => array('My Song'),
            'artist' => array('The Artist'),
            'album' => array('Greatest Hits'),
            'year' => array('2004'),
            'genre' => array('Rock'),
            'comment' => array('excellent!'),
            'track' => array('04/16'),
        );

        return $this->write('real', $data);
    }
}
