<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Stream\Stream;

/**
 * ID3v1 metadata class.
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Metadata
{
    /**
     * @var Stream
     */
    protected $stream;

    /**
     * Create ID3v1 metadata object.
     *
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Returns whether ID3v1 metadata exists.
     *
     * @return bool
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
     * Strip ID3v1 metadata.
     *
     * @return $this
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
     * Read ID3v1 tag.
     *
     * @return null|Tag
     */
    public function read()
    {
        if (!$this->exists()) {
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
            $tag->setTrack($this->stream->readUInt8());
        } else {
            $tag->setComment($this->trimData($this->stream->read(30)));
        }

        $genre = $this->stream->readUInt8();
        if (in_array($genre, Genre::values())) {
            $tag->setGenre($genre);
        }

        return $tag;
    }

    /**
     * Pad data.
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

    /**
     * Write ID3v1 tag.
     *
     * @param Tag $tag The tag to write.
     *
     * @return $this
     */
    public function write(Tag $tag)
    {
        $offset = 0;
        if ($this->exists()) {
            $offset = -128;
        }

        $this->stream->seek($offset, SEEK_END);

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

        $this->stream->write($data);

        return $this;
    }
}
