<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\ID3v1\Reader\TagReader;
use GravityMedia\Metadata\ID3v1\Writer\TagWriter;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v1 metadata.
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
     * @var TagReader
     */
    protected $tagReader;

    /**
     * @var TagWriter
     */
    protected $tagWriter;

    /**
     * Create ID3v1 metadata.
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
     * Get tag writer.
     *
     * @return TagWriter
     */
    public function getTagWriter()
    {
        if (null === $this->tagWriter) {
            $this->tagWriter = new TagWriter($this, $this->stream);
        }

        return $this->tagWriter;
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
        $this->getTagWriter()->write($tag);

        return $this;
    }
}
