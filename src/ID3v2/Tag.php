<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Feature\Picture;

/**
 * ID3v2 tag
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Tag
{
    /**
     * Tag version 2.2
     */
    const VERSION_22 = 22;

    /**
     * Tag version 2.3
     */
    const VERSION_23 = 23;

    /**
     * Tag version 2.4
     */
    const VERSION_24 = 24;

    /**
     * Supported frames
     *
     * @var string[]
     */
    public static $frames = array(
        'TIT2', // title/songname/content description
        'TPE1', // artist(s)/lead performer(s)/soloist(s)
        'TALB', // album/movie/show title
        'TYER', // year
        'COMM', // comments
        'TRCK', // track number/position in set
        'TCON', // genre/content type
        'TPE2', // band/orchestra/accompaniment
        'TIT1', // works/content group description
        'TCOM', // composer(s)
        'TOPE', // original artist(s)/performer(s)
        'TPOS', // disc number/part of a set
        'APIC'  // picture/attached picture
    );

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $artist;

    /**
     * @var string
     */
    protected $album;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var int
     */
    protected $track;

    /**
     * @var int
     */
    protected $trackCount;

    /**
     * @var string
     */
    protected $genre;

    /**
     * @var string
     */
    protected $band;

    /**
     * @var string
     */
    protected $works;

    /**
     * @var string
     */
    protected $composer;

    /**
     * @var string
     */
    protected $originalArtist;

    /**
     * @var int
     */
    protected $disc;

    /**
     * @var int
     */
    protected $discCount;

    /**
     * @var \GravityMedia\Metadata\Feature\Picture
     */
    protected $picture;

    /**
     * Create ID3v1 tag
     *
     * @param string $version
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($version = self::VERSION_24)
    {
        if (!in_array($version, array(self::VERSION_22, self::VERSION_23, self::VERSION_24))) {
            throw new \InvalidArgumentException('Invalid version');
        }
        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setTitle($title)
    {
        if (strlen($title) > 30) {
            throw new \InvalidArgumentException('The title string must not be longer than 30 characters');
        }
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set artist
     *
     * @param string $artist
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setArtist($artist)
    {
        if (strlen($artist) > 30) {
            throw new \InvalidArgumentException('The artist string must not be longer than 30 characters');
        }
        $this->artist = $artist;
        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set album
     *
     * @param string $album
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        if (strlen($album) < 31) {
            throw new \InvalidArgumentException('The album string must not be longer than 30 characters');
        }
        $this->album = $album;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set year
     *
     * @param int $year
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setYear($year)
    {
        if (preg_match('/^\d{4}$/', strval($year)) < 0) {
            throw new \InvalidArgumentException('The year must have exactly 4 digits');
        }
        $this->year = $year;
        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setComment($comment)
    {
        if (self::VERSION_11 === $this->version) {
            if (strlen($comment) > 28) {
                throw new \InvalidArgumentException('The comment string must not be longer than 28 characters');
            }
        } elseif (strlen($comment) > 30) {
            throw new \InvalidArgumentException('The comment string must not be longer than 30 characters');
        }
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set track number
     *
     * @param int $track
     *
     * @throws \BadMethodCallException
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setTrack($track)
    {
        if (self::VERSION_10 === $this->version) {
            throw new \BadMethodCallException('Track is not supported in this version');
        }
        if (preg_match('/^\d{1,2}$/', strval($track)) < 0) {
            throw new \InvalidArgumentException('The track must not have more than 2 digits');
        }
        $this->track = $track;
        return $this;
    }

    /**
     * Get track number
     *
     * @return int
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set track count
     *
     * @param int $trackCount
     *
     * @throws \BadMethodCallException
     *
     * @return $this
     */
    public function setTrackCount($trackCount)
    {
        if (null === $this->track) {
            throw new \BadMethodCallException('The track must be set before the track count');
        }
        $this->trackCount = $trackCount;
        return $this;
    }

    /**
     * Get track count
     *
     * @return int
     */
    public function getTrackCount()
    {
        return $this->trackCount;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return $this
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set band
     *
     * @param string $band
     *
     * @return $this
     */
    public function setBand($band)
    {
        $this->band = $band;
        return $this;
    }

    /**
     * Get band
     *
     * @return string
     */
    public function getBand()
    {
        return $this->band;
    }

    /**
     * Set works
     *
     * @param string $works
     *
     * @return $this
     */
    public function setWorks($works)
    {
        $this->works = $works;
        return $this;
    }

    /**
     * Get works
     *
     * @return string
     */
    public function getWorks()
    {
        return $this->works;
    }

    /**
     * Set composer
     *
     * @param string $composer
     *
     * @return $this
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;
        return $this;
    }

    /**
     * Get composer
     *
     * @return string
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * Set original artist
     *
     * @param string $originalArtist
     *
     * @return $this
     */
    public function setOriginalArtist($originalArtist)
    {
        $this->originalArtist = $originalArtist;
        return $this;
    }

    /**
     * Get original artist
     *
     * @return string
     */
    public function getOriginalArtist()
    {
        return $this->originalArtist;
    }

    /**
     * Set disc
     *
     * @param int $disc
     *
     * @return $this
     */
    public function setDisc($disc)
    {
        $this->disc = $disc;
        return $this;
    }

    /**
     * Get disc
     *
     * @return int
     */
    public function getDisc()
    {
        return $this->disc;
    }

    /**
     * Set disc count
     *
     * @param int $discCount
     *
     * @throws \BadMethodCallException
     *
     * @return $this
     */
    public function setDiscCount($discCount)
    {
        if (null === $this->disc) {
            throw new \BadMethodCallException('The disc must be set before the disc count');
        }
        $this->discCount = $discCount;
        return $this;
    }

    /**
     * Get disc count
     *
     * @return int
     */
    public function getDiscCount()
    {
        return $this->discCount;
    }

    /**
     * Set picture
     *
     * @param \GravityMedia\Metadata\Feature\Picture $picture
     *
     * @return $this
     */
    public function setPicture(Picture $picture)
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * Get picture
     *
     * @return \GravityMedia\Metadata\Feature\Picture
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
