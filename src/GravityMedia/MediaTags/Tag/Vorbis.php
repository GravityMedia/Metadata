<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Tag;

/**
 * Vorbis comment tag
 *
 * @package GravityMedia\MediaTags\Tag
 */
class Vorbis extends AbstractTag
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

        return $this->write('vorbiscomment', $data);
    }
}
