<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1\Reader;

use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\ID3v1\Tag;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\Reader\Integer8Reader;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v1 tag reader
 *
 * @package GravityMedia\Metadata\ID3v1\Reader
 */
class TagReader
{
    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var Integer8Reader
     */
    protected $integer8Reader;

    /**
     * Create ID3v1 tag reader.
     *
     * @param MetadataInterface $metadata
     * @param StreamInterface $stream
     */
    public function __construct(MetadataInterface $metadata, StreamInterface $stream)
    {
        $this->metadata = $metadata;
        $this->stream = $stream;
    }

    /**
     * Get 8-bit integer reader.
     *
     * @return Integer8Reader
     */
    public function getInteger8Reader()
    {
        if (null === $this->integer8Reader) {
            $this->integer8Reader = new Integer8Reader($this->stream);
        }

        return $this->integer8Reader;
    }

    /**
     * Trim data.
     *
     * @param string $data The data to trim
     *
     * @return string
     */
    protected function trimData($data)
    {
        return trim(substr($data, 0, strcspn($data, "\x00")));
    }

    /**
     * Read ID3v1 tag version.
     *
     * @return int
     */
    protected function readVersion()
    {
        $this->stream->seek(-3, SEEK_END);
        if ("\x00" === $this->stream->read(1) && "\x00" !== $this->stream->read(1)) {
            return Version::VERSION_11;
        }

        return Version::VERSION_10;
    }

    /**
     * Read ID3v1 tag.
     *
     * @return null|TagInterface
     */
    public function read()
    {
        if (!$this->metadata->exists()) {
            return null;
        }

        $version = $this->readVersion();
        $tag = new Tag($version);

        $this->stream->seek(-125, SEEK_END);
        $tag
            ->setTitle($this->trimData($this->stream->read(30)))
            ->setArtist($this->trimData($this->stream->read(30)))
            ->setAlbum($this->trimData($this->stream->read(30)))
            ->setYear(intval($this->trimData($this->stream->read(4)), 10));

        if (Version::VERSION_11 === $version) {
            $tag->setComment($this->trimData($this->stream->read(28)));
            $this->stream->seek(1, SEEK_CUR);
            $tag->setTrack($this->getInteger8Reader()->read());
        } else {
            $tag->setComment($this->trimData($this->stream->read(30)));
        }

        $genre = $this->getInteger8Reader()->read();
        if (in_array($genre, Genre::values())) {
            $tag->setGenre($genre);
        }

        return $tag;
    }
}
