<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Encoder\Unsynchronisation;
use GravityMedia\Metadata\ID3v2\Enum\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Tag;
use GravityMedia\Metadata\Metadata\HeaderInterface;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\Stream;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v2 tag reader.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
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
     * @var HeaderReader
     */
    protected $headerReader;

    /**
     * Create ID3v2 tag reader.
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
     * Get header reader.
     *
     * @return HeaderReader
     */
    public function getHeaderReader()
    {
        if (null === $this->headerReader) {
            $this->headerReader = new HeaderReader($this->stream);
        }

        return $this->headerReader;
    }

    /**
     * Read data.
     *
     * @param HeaderInterface $header
     *
     * @return string
     */
    protected function readData(HeaderInterface $header)
    {
        $this->stream->seek(10);
        $data = $this->stream->read($header->getSize());

        if ($header->isFlagEnabled(HeaderFlag::FLAG_COMPRESSION)) {
            $data = gzuncompress($data);
        }

        if ($header->isFlagEnabled(HeaderFlag::FLAG_UNSYNCHRONISATION)) {
            $data = Unsynchronisation::decode($data);
        }

        return $data;
    }

    /**
     * Create data stream.
     *
     * @param string $data
     *
     * @return StreamInterface
     */
    protected function createDataStream($data)
    {
        $resource = fopen('php://temp', 'r+b');
        $stream = Stream::fromResource($resource);
        $stream->write($data);
        $stream->rewind();

        return $stream;
    }

    /**
     * Read ID3v2 tag.
     *
     * @return null|TagInterface
     */
    public function read()
    {
        if (!$this->metadata->exists()) {
            return null;
        }

        $header = $this->getHeaderReader()->read();
        $stream = $this->createDataStream($this->readData($header));

        $tag = new Tag($header);
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
}
