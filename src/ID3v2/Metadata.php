<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\ID3v2\Converter\Unsynchronisation;
use GravityMedia\Metadata\ID3v2\Enum\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Metadata\ExtendedHeaderReader;
use GravityMedia\Metadata\ID3v2\Metadata\FrameReader;
use GravityMedia\Metadata\ID3v2\Metadata\HeaderReader;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\Stream;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v2 metadata
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
     * Create ID3v2 metadata object.
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
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $headerReader = new HeaderReader($this->stream);

        $this->stream->seek(3);
        $header = $headerReader->read();

        $tag = new Tag($header);

        $data = $this->stream->read($header->getSize());
        if ($header->isFlagEnabled(HeaderFlag::FLAG_COMPRESSION)) {
            $data = gzuncompress($data);
        }

        if ($header->isFlagEnabled(HeaderFlag::FLAG_UNSYNCHRONISATION)) {
            $data = Unsynchronisation::decode($data);
        }

        $resource = fopen('php://temp', 'r+b');
        $stream = Stream::fromResource($resource);
        $stream->write($data);
        $stream->rewind();

        $size = $stream->getSize();

        if ($header->isFlagEnabled(HeaderFlag::FLAG_EXTENDED_HEADER)) {
            $extendedHeaderReader = new ExtendedHeaderReader($stream, $header);
            $extendedHeader = $extendedHeaderReader->read();

            $tag->setExtendedHeader($extendedHeader);

            $size -= $extendedHeader->getSize();
        }

        if ($header->isFlagEnabled(HeaderFlag::FLAG_EXTENDED_HEADER)) {
            // TODO: Read footer from stream

            $size -= 10;
        }

        $frameReader = new FrameReader($stream, $header);
        while ($size > 0) {
            $frame = $frameReader->read();
            if (0 === $frame->getSize()) {
                break;
            }

            $tag->addFrame($frame);

            $size -= $frame->getSize();
        }

        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function write(TagInterface $tag)
    {
        return $this;
    }
}
