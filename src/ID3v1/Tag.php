<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception\BadMethodCallException;
use GravityMedia\Metadata\Exception\InvalidArgumentException;
use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\Metadata\TagInterface;

/**
 * ID3v1 tag
 *
 * @package GravityMedia\Metadata
 */
class Tag implements TagInterface
{
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
     * @var int
     */
    protected $genre;

    /**
     * Create tag object.
     *
     * @param int $version The version (default is 1: v1.1)
     *
     * @throws InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = Version::VERSION_11)
    {
        if (!in_array($version, Version::values())) {
            throw new InvalidArgumentException('Invalid version.');
        }

        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title The title
     *
     * @throws InvalidArgumentException An exception is thrown when the title exceeds 30 characters
     *
     * @return $this
     */
    public function setTitle($title)
    {
        if (strlen($title) > 30) {
            throw new InvalidArgumentException('Title argument exceeds maximum number of characters.');
        }

        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set artist
     *
     * @param string $artist The artist
     *
     * @throws InvalidArgumentException An exception is thrown when the artist exceeds 30 characters
     *
     * @return $this
     */
    public function setArtist($artist)
    {
        if (strlen($artist) > 30) {
            throw new InvalidArgumentException('Artist argument exceeds maximum number of characters.');
        }

        $this->artist = $artist;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set album
     *
     * @param string $album The album
     *
     * @throws InvalidArgumentException An exception is thrown when the album exceeds 30 characters
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        if (strlen($album) > 30) {
            throw new InvalidArgumentException('Album argument exceeds maximum number of characters.');
        }

        $this->album = $album;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year
     *
     * @param int $year The year
     *
     * @throws InvalidArgumentException An exception is thrown when the year does not have exactly 4 digits
     *
     * @return $this
     */
    public function setYear($year)
    {
        if (preg_match('/^\d{4}$/', $year) < 1) {
            throw new InvalidArgumentException('Year argument must have exactly 4 digits.');
        }

        $this->year = $year;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment
     *
     * @param string $comment The comment
     *
     * @throws InvalidArgumentException An exception is thrown when the comment exceeds 28 characters
     *                                            (ID3 v1.1) or 30 characters (ID3 v1.0)
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $max = Version::VERSION_11 === $this->version ? 28 : 30;
        if (strlen($comment) > $max) {
            throw new InvalidArgumentException('Comment argument exceeds maximum number of characters.');
        }

        $this->comment = $comment;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set track
     *
     * @param int $track The track number
     *
     * @throws BadMethodCallException An exception is thrown on ID3 v1.0 tag
     * @throws InvalidArgumentException An exception is thrown when the year does not have exactly 2 digits
     *
     * @return $this
     */
    public function setTrack($track)
    {
        if (Version::VERSION_10 === $this->version) {
            throw new BadMethodCallException('Track is not supported in this version.');
        }

        if (preg_match('/^\d{1,2}$/', $track) < 1) {
            throw new InvalidArgumentException('Track argument exceeds 2 digits.');
        }

        $this->track = $track;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set genre
     *
     * @param int $genre
     *
     * @throws InvalidArgumentException An exception is thrown on invalid genre arguments
     *
     * @return $this
     */
    public function setGenre($genre)
    {
        if (!in_array($genre, Genre::values())) {
            throw new InvalidArgumentException('Invalid genre.');
        }

        $this->genre = $genre;

        return $this;
    }
}
