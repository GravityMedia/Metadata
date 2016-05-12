<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\InvalidArgumentException;
use GravityMedia\Metadata\Exception\RuntimeException;
use GravityMedia\Metadata\ID3v2\Filter\CompressionFilter;
use GravityMedia\Metadata\ID3v2\Filter\UnsynchronisationFilter;
use GravityMedia\Metadata\ID3v2\Flag\FrameFlag;
use GravityMedia\Metadata\ID3v2\Flag\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Metadata\ExtendedHeaderHandler;
use GravityMedia\Metadata\ID3v2\Metadata\Frame\CommentFrame;
use GravityMedia\Metadata\ID3v2\Metadata\Frame\TextFrame;
use GravityMedia\Metadata\ID3v2\Metadata\FrameHandler;
use GravityMedia\Metadata\ID3v2\Metadata\HeaderHandler;
use GravityMedia\Stream\ByteOrder;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 metadata class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Metadata
{
    /**
     * @var Stream
     */
    private $stream;

    /**
     * @var CompressionFilter
     */
    private $compressionFilter;

    /**
     * @var UnsynchronisationFilter
     */
    private $unsynchronisationFilter;

    /**
     * Create ID3v2 metadata object from resource.
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
        $stream->setByteOrder(ByteOrder::BIG_ENDIAN);

        $metadata = new static();
        $metadata->stream = $stream;
        $metadata->compressionFilter = new CompressionFilter();
        $metadata->unsynchronisationFilter = new UnsynchronisationFilter();

        return $metadata;
    }

    /**
     * Returns whether ID3v2 metadata exists.
     *
     * @return bool
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
     * Strip ID3v2 metadata.
     *
     * @return $this
     */
    public function strip()
    {
        // TODO: implement

        return $this;
    }

    /**
     * Read ID3v2 version.
     *
     * @throws RuntimeException An exception is thrown on invalid versions.
     *
     * @return int
     */
    protected function readVersion()
    {
        $this->stream->seek(3);

        switch ($this->stream->readUInt8()) {
            case 2:
                return Version::VERSION_22;
            case 3:
                return Version::VERSION_23;
            case 4:
                return Version::VERSION_24;
        }

        throw new RuntimeException('Invalid version.');
    }

    /**
     * Read ID3v2 revision.
     *
     * @return int
     */
    protected function readRevision()
    {
        $this->stream->seek(4);

        return $this->stream->readUInt8();
    }

    /**
     * Create readable stream from data.
     *
     * @param string $data
     *
     * @return Stream
     */
    protected function createReadableStreamFromData($data)
    {
        $filename = tempnam(sys_get_temp_dir(), 'php');
        file_put_contents($filename, $data);

        $stream = Stream::fromResource(fopen($filename, 'r'));
        $stream->setByteOrder(ByteOrder::BIG_ENDIAN);

        return $stream;
    }

    /**
     * Read ID3v2 tag.
     *
     * @return null|Tag
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $version = $this->readVersion();
        $tag = new Tag($version, $this->readRevision());

        $headerHandler = new HeaderHandler($this->stream, $version);
        $length = $headerHandler->getSize();

        $this->stream->seek(10);
        $data = $this->stream->read($length);
        if ($headerHandler->isFlagEnabled(HeaderFlag::FLAG_COMPRESSION)) {
            $data = $this->compressionFilter->decode($data);
        }
        if ($headerHandler->isFlagEnabled(HeaderFlag::FLAG_UNSYNCHRONISATION)) {
            $data = $this->unsynchronisationFilter->decode($data);
        }

        $tagStream = $this->createReadableStreamFromData($data);
        $tagLength = $tagStream->getSize();

        if ($headerHandler->isFlagEnabled(HeaderFlag::FLAG_EXTENDED_HEADER)) {
            $extendedHeaderHandler = new ExtendedHeaderHandler($tagStream, $version);
            $tagLength -= $extendedHeaderHandler->getSize();

            $tag->setPadding($extendedHeaderHandler->getPadding());
            $tag->setCrc32($extendedHeaderHandler->getCrc32());
            $tag->setRestrictions($extendedHeaderHandler->getRestrictions());
        }

        if ($headerHandler->isFlagEnabled(HeaderFlag::FLAG_FOOTER_PRESENT)) {
            // TODO: Read footer metadata.

            $tagLength -= 10;
        }

        while ($tagLength > 0) {
            $frameHandler = new FrameHandler($tagStream, $version);

            $frameName = $frameHandler->getName();
            $frameLength = $frameHandler->getSize();
            $tagLength -= $frameLength;

            if (0 === $frameLength) {
                break;
            }

            $data = $tagStream->read($frameHandler->getDataLength());
            if ($frameHandler->isFlagEnabled(FrameFlag::FLAG_COMPRESSION)) {
                $data = $this->compressionFilter->decode($data);
            }
            if ($frameHandler->isFlagEnabled(FrameFlag::FLAG_UNSYNCHRONISATION)) {
                $data = $this->unsynchronisationFilter->decode($data);
            }

            $frameStream = $this->createReadableStreamFromData($data);

            $frame = new Frame();
            $frame->setName($frameName);
            if ('COMM' === $frameName) {
                $commentFrame = new CommentFrame($frameStream);
                $frame->setContent($commentFrame->getText());

            } elseif ('T' === substr($frameName, 0, 1)) {
                $textFrame = new TextFrame($frameStream);
                $frame->setContent($textFrame->getText());

                if ('TXXX' === $frameName) {
                    // TODO: Read user defined text frame.
                }
            }

            $tag->addFrame($frame);
        }

        return $tag;
    }

    /**
     * Write ID3v2 tag.
     *
     * @param Tag $tag The tag to write.
     *
     * @return $this
     */
    public function write(Tag $tag)
    {
        $stream = Stream::fromResource(fopen('php://temp', 'w'));
        $stream->setByteOrder(ByteOrder::BIG_ENDIAN);

        $stream->write('ID3');
        $stream->writeUInt8($tag->getVersion());
        $stream->writeUInt8($tag->getRevision());

        //$headerHandler = new HeaderHandler($stream, $tag->getVersion());

        $tagStream = Stream::fromResource(fopen('php://temp', 'w'));
        $tagStream->setByteOrder(ByteOrder::BIG_ENDIAN);
        
        foreach ($tag->getFrames() as $frame) {
            $frameHandler = new FrameHandler($tagStream, $tag->getVersion());
            $frameHandler->setName($frame->getName());

        }

        return $this;
    }
}
