<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v1 metadata
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Metadata implements MetadataInterface
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create stream provider object from stream.
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function exists()
    {
        if ($this->stream->getSize() < 128) {
            return false;
        }

        $this->stream->seek(-128, SEEK_END);

        return 'TAG' === $this->stream->read(3);
    }

    /**
     * {@inheritdoc}
     */
    public function strip()
    {
        if (!$this->exists()) {
            return $this;
        }

        $this->stream->seek(0);
        $this->stream->truncate($this->stream->getSize() - 128);

        return $this;
    }

    /**
     * Trim data
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
     * {@inheritdoc}
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $this->stream->seek(-3, SEEK_END);
        $version = Version::VERSION_10;
        if ("\x00" === $this->stream->read(1) && "\x00" !== $this->stream->read(1)) {
            $version = Version::VERSION_11;
        }

        $this->stream->seek(-125, SEEK_END);
        $tag = new Tag($version);
        $tag
            ->setTitle($this->trimData($this->stream->read(30)))
            ->setArtist($this->trimData($this->stream->read(30)))
            ->setAlbum($this->trimData($this->stream->read(30)))
            ->setYear(intval($this->trimData($this->stream->read(4)), 10));

        if (Version::VERSION_11 === $version) {
            $tag->setComment($this->trimData($this->stream->read(28)));
            $this->stream->seek(1, SEEK_CUR);
            $tag->setTrack(ord($this->stream->read(1)));
        } else {
            $tag->setComment($this->trimData($this->stream->read(30)));
        }

        $genre = ord($this->stream->read(1));
        if (in_array($genre, Genre::values())) {
            $tag->setGenre($genre);
        }

        return $tag;
    }

    /**
     * Pad data
     *
     * @param string $data The data to pad
     * @param int $length The final length
     * @param int $type The type of padding
     *
     * @return string
     */
    protected function padData($data, $length, $type)
    {
        return str_pad(trim(substr($data, 0, $length)), $length, "\x00", $type);
    }

    /**
     * {@inheritdoc}
     */
    public function write(TagInterface $tag)
    {
        $offset = 0;
        if ($this->exists()) {
            $offset = -128;
        }

        $data = 'TAG';
        $data .= $this->padData($tag->getTitle(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getArtist(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getAlbum(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getYear(), 4, STR_PAD_LEFT);

        if (Version::VERSION_11 === $tag->getVersion()) {
            $data .= $this->padData($tag->getComment(), 28, STR_PAD_RIGHT);
            $data .= "\x00";
            $data .= chr($tag->getTrack());
        } else {
            $data .= $this->padData($tag->getComment(), 30, STR_PAD_RIGHT);
        }

        $data .= chr($tag->getGenre());

        $this->stream->seek($offset, SEEK_END);
        $this->stream->write($data);

        return $this;
    }
}
