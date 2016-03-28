<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\ID3v2\Reader\TagReader;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v2 metadata.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Metadata implements MetadataInterface
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var TagReader
     */
    protected $tagReader;

    /**
     * Create ID3v2 metadata.
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Get tag reader.
     *
     * @return TagReader
     */
    public function getTagReader()
    {
        if (null === $this->tagReader) {
            $this->tagReader = new TagReader($this, $this->stream);
        }

        return $this->tagReader;
    }

    /**
     * {@inheritdoc}
     */
    public function exists()
    {
        if ($this->stream->getSize() < 10) {
            return false;
        }

        $this->stream->seek(0);

        return 'ID3' === $this->stream->read(3);
    }

    /**
     * {@inheritdoc}
     */
    public function strip()
    {
        // TODO: implement

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        return $this->getTagReader()->read();
    }

    /**
     * {@inheritdoc}
     */
    public function write(TagInterface $tag)
    {
        // TODO: implement

        return $this;
    }
}
