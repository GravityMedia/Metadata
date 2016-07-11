<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception\InvalidArgumentException;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 metadata class.
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Metadata
{
    /**
     * The stream.
     *
     * @var Stream
     */
    protected $stream;

    /**
     * The filter.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Create ID3v1 metadata object from resource.
     *
     * @param resource $resource
     *
     * @throws InvalidArgumentException An exception will be thrown for invalid resource arguments.
     *
     * @return static
     */
    public static function fromResource($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Invalid resource');
        }

        $stream = Stream::fromResource($resource);

        $metadata = new static();
        $metadata->stream = $stream;
        $metadata->filter = new Filter();

        return $metadata;
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
        $tag->setTitle($this->filter->decode($this->stream->read(30)));
        $tag->setArtist($this->filter->decode($this->stream->read(30)));
        $tag->setAlbum($this->filter->decode($this->stream->read(30)));
        $tag->setYear($this->filter->decode($this->stream->read(4)));

        if (Version::VERSION_11 === $version) {
            $tag->setComment($this->filter->decode($this->stream->read(28)));
            $this->stream->seek(1, SEEK_CUR);
            $tag->setTrack($this->stream->readUInt8());
        } else {
            $tag->setComment($this->filter->decode($this->stream->read(30)));
        }

        $genre = $this->stream->readUInt8();
        if (in_array($genre, Genre::values())) {
            $tag->setGenre($genre);
        }

        return $tag;
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
        $data .= $this->filter->encode($tag->getTitle(), 30);
        $data .= $this->filter->encode($tag->getArtist(), 30);
        $data .= $this->filter->encode($tag->getAlbum(), 30);
        $data .= $this->filter->encode($tag->getYear(), 4);

        if (Version::VERSION_11 === $tag->getVersion()) {
            $data .= $this->filter->encode($tag->getComment(), 28);
            $data .= "\x00";
            $data .= $this->stream->writeUInt8($tag->getTrack());
        } else {
            $data .= $this->filter->encode($tag->getComment(), 30);
        }

        $data .= $this->stream->writeUInt8($tag->getGenre());

        $this->stream->write($data);

        return $this;
    }
}
