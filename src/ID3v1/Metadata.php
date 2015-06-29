<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\MetadataInterface;
use GravityMedia\Metadata\TagInterface;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 metadata
 *
 * @package GravityMedia\Metadata
 */
class Metadata implements MetadataInterface
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var \GravityMedia\Stream\StreamInterface
     */
    protected $stream;

    /**
     * Create ID3v1 metadata object
     *
     * @param \SplFileInfo $file
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * Get stream
     *
     * @return \GravityMedia\Stream\StreamInterface
     */
    public function getStream()
    {
        if (null === $this->stream) {
            if ($this->file->isFile()) {
                $this->stream = new Stream($this->file, 'r+b');
            } else {
                $this->stream = new Stream($this->file, 'w+b');
            }
        }

        return $this->stream;
    }

    /**
     * @inheritdoc
     */
    public function exists()
    {
        $stream = $this->getStream();
        if ($stream->getSize() < 128) {
            return false;
        }

        $stream->seek(-128, SEEK_END);

        return 'TAG' === $stream->getReader()->read(3);
    }

    /**
     * @inheritdoc
     */
    public function strip()
    {
        if (!$this->exists()) {
            return $this;
        }

        $stream = $this->getStream();
        $stream->seek(0);
        $stream->getWriter()->truncate($stream->getSize() - 128);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $stream = $this->getStream();
        $stream->seek(-3, SEEK_END);

        $reader = $stream->getReader();
        $version = Tag::VERSION_10;
        if ("\x00" === $reader->read(1) && "\x00" !== $reader->read(1)) {
            $version = Tag::VERSION_11;
        }

        $tag = new Tag($version);
        $stream->seek(-125, SEEK_END);
        $tag
            ->setTitle($this->trimData($reader->read(30)))
            ->setArtist($this->trimData($reader->read(30)))
            ->setAlbum($this->trimData($reader->read(30)))
            ->setYear($this->trimData($reader->read(4)));

        if (Tag::VERSION_11 === $tag->getVersion()) {
            $tag->setComment($this->trimData($reader->read(28)));
            $stream->seek(1, SEEK_CUR);
            $tag->setTrack(ord($reader->read(1)));
        } else {
            $tag->setComment($this->trimData($reader->read(30)));
        }

        $genreId = ord($reader->read(1));
        if (isset(Tag::$genres[$genreId])) {
            $tag->setGenre(Tag::$genres[$genreId]);
        }

        return $tag;
    }

    /**
     * @inheritdoc
     */
    public function write(TagInterface $tag)
    {
        if (!$tag instanceof Tag) {
            throw new Exception\InvalidArgumentException('Invalid tag argument');
        }

        $data = 'TAG';
        $data .= $this->padData($tag->getTitle(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getArtist(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getAlbum(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getYear(), 4, STR_PAD_LEFT);

        if (Tag::VERSION_11 === $tag->getVersion()) {
            $data .= $this->padData($tag->getComment(), 28, STR_PAD_RIGHT);
            $data .= "\x00";
            $data .= chr($tag->getTrack());
        } else {
            $data .= $this->padData($tag->getComment(), 30, STR_PAD_RIGHT);
        }

        $genreId = array_search($tag->getGenre(), Tag::$genres);
        $data .= chr(false === $genreId ? 255 : $genreId);

        $stream = $this->getStream();
        if ($this->exists()) {
            $stream->seek(-128, SEEK_END);
        } else {
            $stream->seek(0, SEEK_END);
        }

        $stream->getWriter()->write($data);

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
