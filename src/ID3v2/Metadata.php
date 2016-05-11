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
use GravityMedia\Metadata\ID3v2\Flag\ExtendedHeaderFlag;
use GravityMedia\Metadata\ID3v2\Flag\FrameFlag;
use GravityMedia\Metadata\ID3v2\Flag\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Metadata\ExtendedHeaderMetadata;
use GravityMedia\Metadata\ID3v2\Metadata\Frame\TextInformationFrame;
use GravityMedia\Metadata\ID3v2\Metadata\FrameMetadata;
use GravityMedia\Metadata\ID3v2\Metadata\HeaderMetadata;
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
    protected $stream;

    /**
     * @var CompressionFilter
     */
    protected $compressionFilter;

    /**
     * @var UnsynchronisationFilter
     */
    protected $unsynchronisationFilter;

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
        $revision = $this->readRevision();
        $tag = new Tag($version, $revision);

        $headerMetadata = new HeaderMetadata($this->stream, $version);
        $size = $headerMetadata->readSize();
        $headerFlags = new Flags($headerMetadata->readFlags());

        $this->stream->seek(10);
        $data = $this->stream->read($size);

        if ($headerFlags->isEnabled(HeaderFlag::FLAG_COMPRESSION)) {
            $data = $this->compressionFilter->decode($data);
        }

        if ($headerFlags->isEnabled(HeaderFlag::FLAG_UNSYNCHRONISATION)) {
            $data = $this->unsynchronisationFilter->decode($data);
        }

        $tagStream = $this->createReadableStreamFromData($data);
        $tagSize = $tagStream->getSize();

        if ($headerFlags->isEnabled(HeaderFlag::FLAG_EXTENDED_HEADER)) {
            $extendedHeaderMetadata = new ExtendedHeaderMetadata($tagStream, $version);
            $tagSize -= $extendedHeaderMetadata->readSize();
            $extendedHeaderFlags = new Flags($extendedHeaderMetadata->readFlags());

            $tag->setPadding($extendedHeaderMetadata->readPadding());

            if ($extendedHeaderFlags->isEnabled(ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE)) {
                $tagStream->seek(1, SEEK_CUR);
            }

            if ($extendedHeaderFlags->isEnabled(ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT)) {
                $tag->setCrc32($extendedHeaderMetadata->readCrc32());
            }

            if ($extendedHeaderFlags->isEnabled(ExtendedHeaderFlag::FLAG_TAG_RESTRICTIONS)) {
                $tag->setRestrictions($extendedHeaderMetadata->readRestrictions());
            }
        }

        if ($headerFlags->isEnabled(HeaderFlag::FLAG_FOOTER_PRESENT)) {
            // TODO: Read footer metadata.

            $tagSize -= 10;
        }

        $frameMetadata = new FrameMetadata($tagStream, $version);

        while ($tagSize > 0) {
            $frameName = $frameMetadata->readName();
            $frameSize = $frameMetadata->readSize();
            $tagSize -= $frameSize;

            if (0 === $frameSize) {
                break;
            }

            $frame = new Frame();
            $frame->setName($frameName);

            $frameFlags = new Flags($frameMetadata->readFlags());

            if ($frameFlags->isEnabled(FrameFlag::FLAG_DATA_LENGT_INDICATOR)) {
                $frame->setDataLength($frameMetadata->readDataLength());

                $frameSize -= 4;
            }

            $data = $tagStream->read($frameSize);

            if ($frameFlags->isEnabled(FrameFlag::FLAG_COMPRESSION)) {
                $data = $this->compressionFilter->decode($data);
            }

            if ($frameFlags->isEnabled(FrameFlag::FLAG_UNSYNCHRONISATION)) {
                $data = $this->unsynchronisationFilter->decode($data);
            }

            $frame->setData($data);

            $tag->addFrame($frame);

            $frameStream = $this->createReadableStreamFromData($data);

            if ('T' === substr($frameName, 0, 1)) {
                $textInformationFrame = new TextInformationFrame($frameStream, $version);
                $textEncoding = $textInformationFrame->readTextEncoding();

                //var_dump($textEncoding);
                //var_dump($textInformationFrame->readInformation($textEncoding));
            }
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
        // TODO: implement

        return $this;
    }
}
