<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Tag;

use GetId3\Module\Tag\Id3v1 as Id3v1Processor;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * ID3 V1 tag
 *
 * @package GravityMedia\MediaTags\Tag
 */
class Id3v1 extends AbstractTag
{
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
     * @var string
     */
    protected $genre;

    /**
     * @var string[]
     */
    private $availableGenres;

    /**
     * Save tag
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function save()
    {
        $hydrator = new ClassMethods();
        $properties = $hydrator->extract($this);
        $data = array();

        unset($properties['audio_properties']);
        foreach ($properties as $name => $value) {
            if (null !== $value) {
                $data[$name] = array($value);
            }
        }

        return $this->write('id3v1', $data);
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setTitle($title)
    {
        if (strlen($title) < 31) {
            $this->title = $title;
            return $this;
        }
        throw new \BadMethodCallException('The title string must not be longer than 30 characters');
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
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setArtist($artist)
    {
        if (strlen($artist) < 31) {
            $this->artist = $artist;
            return $this;
        }
        throw new \BadMethodCallException('The artist string must not be longer than 30 characters');
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
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setAlbum($album)
    {
        if (strlen($album) < 31) {
            $this->album = $album;
            return $this;
        }
        throw new \BadMethodCallException('The album string must not be longer than 30 characters');
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
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setYear($year)
    {
        if (empty($year) || preg_match('/^\d{4}$/', strval($year)) > 0) {
            $this->year = $year;
            return $this;
        }
        throw new \BadMethodCallException('The year must have exactly 4 digits');
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
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setComment($comment)
    {
        if (strlen($comment) < 31) {
            $this->comment = $comment;
            return $this;
        }
        throw new \BadMethodCallException('The comment string must not be longer than 30 characters');
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
     * @return $this
     */
    public function setTrack($track)
    {
        if (empty($track) || preg_match('/^\d{1,2}$/', strval($track)) > 0) {
            $this->track = $track;
            return $this;
        }
        throw new \BadMethodCallException('The track must not have more than 2 digits');
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
     * Set genre
     *
     * @param string $genre
     *
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setGenre($genre)
    {
        if (!is_array($this->availableGenres)) {
            $this->availableGenres = array_values(Id3v1Processor::ArrayOfGenres());
        }
        if (in_array($genre, $this->availableGenres)) {
            $this->genre = $genre;
            return $this;
        }
        throw new \BadMethodCallException(sprintf('The genre "%s" is not available', $genre));
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
}
