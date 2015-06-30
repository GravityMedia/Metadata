<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 tag factory
 *
 * @package GravityMedia\Metadata
 */
class TagFactory
{
    /**
     * @var Genres
     */
    protected $genres;

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
     * Create ID3v1 tag object
     *
     * @param string $data The byte vector which represents the tag
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid data argument
     *
     * @return TagInterface
     */
    public function createTag($data)
    {
        $stream = new Stream('php://temp', 'w+b');
        $reader = $stream->getReader();
        $writer = $stream->getWriter();

        $writer->write($data);
        $stream->rewind();

        if (128 !== $stream->getSize() || 'TAG' !== $reader->read(3)) {
            throw new Exception\InvalidArgumentException('Invalid data argument');
        }

        $stream->seek(-3, SEEK_END);
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

        $genre = $this->getGenres()->getNameByIndex(ord($reader->read(1)));
        if (null !== $genre) {
            $tag->setGenre($genre);
        }

        return $tag;
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
}
