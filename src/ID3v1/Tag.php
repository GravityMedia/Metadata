<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\Metadata\TagInterface;

/**
 * ID3v1 tag
 *
 * @package GravityMedia\Metadata
 */
class Tag implements TagInterface
{
    /**
     * Tag version 1.0
     */
    const VERSION_10 = 0;

    /**
     * Tag version 1.1
     */
    const VERSION_11 = 1;

    /**
     * Valid versions
     *
     * @var array
     */
    protected static $validVersions = array(self::VERSION_10, self::VERSION_11);

    /**
     * @var int
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
     * @var Genres
     */
    protected $genres;

    /**
     * @var string
     */
    protected $genre;

    /**
     * Create ID3v1 tag object
     *
     * @param int $version The version (default is 1: v1.1)
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = self::VERSION_11)
    {
        if (!in_array($version, self::$validVersions)) {
            throw new Exception\InvalidArgumentException('Invalid version argument');
        }

        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set title
     *
     * @param string $title The title
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the title exceeds 30 characters
     *
     * @return $this
     */
    public function setTitle($title)
    {
        if (strlen($title) > 30) {
            throw new Exception\InvalidArgumentException('Title argument exceeds maximum number of characters');
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
     * @param string $artist The artist
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the artist exceeds 30 characters
     *
     * @return $this
     */
    public function setArtist($artist)
    {
        if (strlen($artist) > 30) {
            throw new Exception\InvalidArgumentException('Artist argument exceeds maximum number of characters');
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
     * @param string $album The album
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the album exceeds 30 characters
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        if (strlen($album) > 30) {
            throw new Exception\InvalidArgumentException('Album argument exceeds maximum number of characters');
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
     * @param int $year The year
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the year does not have exactly 4 digits
     *
     * @return $this
     */
    public function setYear($year)
    {
        if (preg_match('/^\d{4}$/', $year) < 1) {
            throw new Exception\InvalidArgumentException('Year argument must have exactly 4 digits');
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
     * @param string $comment The comment
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the comment exceeds 28 characters
     *                                            (ID3 v1.1) or 30 characters (ID3 v1.0)
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $max = self::VERSION_11 === $this->version ? 28 : 30;
        if (strlen($comment) > $max) {
            throw new Exception\InvalidArgumentException('Comment argument exceeds maximum number of characters');
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
     * @param int $track The track number
     *
     * @throws Exception\BadMethodCallException An exception is thrown on ID3 v1.0 tag
     * @throws Exception\InvalidArgumentException An exception is thrown when the year does not have exactly 2 digits
     *
     * @return $this
     */
    public function setTrack($track)
    {
        if (self::VERSION_10 === $this->version) {
            throw new Exception\BadMethodCallException('Track is not supported in this version');
        }

        if (preg_match('/^\d{1,2}$/', $track) < 1) {
            throw new Exception\InvalidArgumentException('Track argument exceeds 2 digits');
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
     * Get genres
     *
     * @return Genres
     */
    public function getGenres()
    {
        if (null === $this->genres) {
            $this->genres = new Genres();
        }

        return $this->genres;
    }

    /**
     * Set genre
     *
     * @param string $genre The genre
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid genre arguments
     *
     * @return $this
     */
    public function setGenre($genre)
    {
        if (255 === $this->getGenres()->getIndexByName($genre)) {
            throw new Exception\InvalidArgumentException('Invalid genre argument');
        }

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
     * @inheritdoc
     */
    public function render()
    {
        $data = 'TAG';
        $data .= $this->padData($this->getTitle(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($this->getArtist(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($this->getAlbum(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($this->getYear(), 4, STR_PAD_LEFT);

        if (self::VERSION_11 === $this->getVersion()) {
            $data .= $this->padData($this->getComment(), 28, STR_PAD_RIGHT);
            $data .= "\x00";
            $data .= chr($this->getTrack());
        } else {
            $data .= $this->padData($this->getComment(), 30, STR_PAD_RIGHT);
        }

        $data .= chr($this->getGenres()->getIndexByName($this->getGenre()));

        return $data;
    }

    /**
     * Pad data
     *
     * @param string $data   The data to pad
     * @param int    $length The final length
     * @param int    $type   The type of padding
     *
     * @return string
     */
    protected function padData($data, $length, $type)
    {
        return str_pad(trim(substr($data, 0, $length)), $length, "\x00", $type);
    }
}
