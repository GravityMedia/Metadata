<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
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
     * @var TagFactory
     */
    protected $tagFactory;

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
     * Get tag factory
     *
     * @return TagFactory
     */
    public function getTagFactory()
    {
        if (null === $this->tagFactory) {
            $this->tagFactory = new TagFactory();
        }

        return $this->tagFactory;
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
        $stream->seek(-128, SEEK_END);

        return $this->getTagFactory()
            ->createTag($stream->getReader()->read(128));
    }

    /**
     * @inheritdoc
     */
    public function write(TagInterface $tag)
    {
        if (!$tag instanceof Tag) {
            throw new Exception\InvalidArgumentException('Invalid tag argument');
        }

        $stream = $this->getStream();
        if ($this->exists()) {
            $stream->seek(-128, SEEK_END);
        } else {
            $stream->seek(0, SEEK_END);
        }

        $stream->getWriter()->write($tag->render());

        return $this;
    }
}
