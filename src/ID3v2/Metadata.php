<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\RuntimeException;
use GravityMedia\Metadata\ID3v2\Enum\Flag;
use GravityMedia\Metadata\ID3v2\Enum\Version;
use GravityMedia\Metadata\Metadata\ExtendedHeaderInterface;
use GravityMedia\Metadata\Metadata\HeaderInterface;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\Enum\ByteOrder;
use GravityMedia\Stream\Reader\CharReader;
use GravityMedia\Stream\Reader\LongReader;
use GravityMedia\Stream\Reader\ShortReader;
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
     * Create stream provider object from stream.
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
     * Decode the given 28-bit synchsafe integer to regular 32-bit integer.
     *
     * @param int $data
     *
     * @return int
     */
    protected function decodeSynchsafe32($data)
    {
        return ($data & 0x7f) | ($data & 0x7f00) >> 1 |
        ($data & 0x7f0000) >> 2 | ($data & 0x7f000000) >> 3;
    }

    /**
     * Decode unsynchronisation.
     *
     * @param string $data
     *
     * @return string
     */
    protected function decodeUnsynchronisation($data)
    {
        return preg_replace('/\xff\x00\x00/', "\xff\x00", preg_replace('/\xff\x00(?=[\xe0-\xff])/', "\xff", $data));
    }

    /**
     * Read header from stream.
     *
     * @param StreamInterface $stream
     *
     * @return HeaderInterface
     */
    protected function readHeaderFromStream(StreamInterface $stream)
    {
        $charReader = new CharReader($stream);

        $longReader = new LongReader($stream);
        $longReader->setByteOrder(ByteOrder::BIG_ENDIAN);

        switch ($charReader->read()) {
            case 2:
                $version = Version::VERSION_22;
                break;
            case 3:
                $version = Version::VERSION_23;
                break;
            case 4:
                $version = Version::VERSION_24;
                break;
            default:
                throw new RuntimeException('Invalid version.');
        }

        $header = new Header($version);
        $header->setRevision($charReader->read());

        $flags = $charReader->read();
        switch ($version) {
            case Version::VERSION_22:
                $header->setFlags([
                    Flag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    Flag::FLAG_COMPRESSION => (bool)($flags & 0x40)
                ]);
                break;
            case Version::VERSION_23:
                $header->setFlags([
                    Flag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    Flag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                    Flag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
                ]);
                break;
            case Version::VERSION_24:
                $header->setFlags([
                    Flag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    Flag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                    Flag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20),
                    Flag::FLAG_FOOTER_PRESENT => (bool)($flags & 0x10)
                ]);
                break;
        }

        $header->setSize($this->decodeSynchsafe32($longReader->read()));

        return $header;
    }

    /**
     * Read extended header from stream.
     *
     * @param StreamInterface $stream
     * @param HeaderInterface $header
     *
     * @return ExtendedHeaderInterface
     */
    protected function readExtendedHeaderFromStream(StreamInterface $stream, HeaderInterface $header)
    {
        $charReader = new CharReader($stream);

        $shortReader = new ShortReader($stream);
        $shortReader->setByteOrder(ByteOrder::BIG_ENDIAN);

        $longReader = new LongReader($stream);
        $longReader->setByteOrder(ByteOrder::BIG_ENDIAN);

        $extendedHeader = new ExtendedHeader();

        switch ($header->getVersion()) {
            case Version::VERSION_23:
                $extendedHeader->setSize($longReader->read());

                $flags = $shortReader->read();
                $extendedHeader->setFlags([
                    Flag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x8000)
                ]);

                $extendedHeader->setPadding($longReader->read());

                if ($extendedHeader->isFlagEnabled(Flag::FLAG_CRC_DATA_PRESENT)) {
                    $extendedHeader->setCrc32($longReader->read());
                }

                break;
            case Version::VERSION_24:
                $extendedHeader->setSize($this->decodeSynchsafe32($longReader->read()));

                $stream->seek(1, SEEK_CUR);
                $flags = $charReader->read();
                $extendedHeader->setFlags([
                    Flag::FLAG_TAG_IS_AN_UPDATE => (bool)($flags & 0x40),
                    Flag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x20),
                    Flag::FLAG_TAG_RESTRICTIONS => (bool)($flags & 0x10)
                ]);

                if ($extendedHeader->isFlagEnabled(Flag::FLAG_TAG_IS_AN_UPDATE)) {
                    $stream->seek(1, SEEK_CUR);
                }

                if ($extendedHeader->isFlagEnabled(Flag::FLAG_CRC_DATA_PRESENT)) {
                    $stream->seek(1, SEEK_CUR);
                    $extendedHeader->setCrc32(
                        $charReader->read() * (0xfffffff + 1) + $this->decodeSynchsafe32($longReader->read())
                    );
                }

                if ($extendedHeader->isFlagEnabled(Flag::FLAG_TAG_RESTRICTIONS)) {
                    $stream->seek(1, SEEK_CUR);
                    $extendedHeader->setRestrictions($charReader->read());
                }

                break;
        }

        return $extendedHeader;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $this->stream->seek(3);

        $header = $this->readHeaderFromStream($this->stream);
        $tag = new Tag($header);

        $this->stream->seek(10);

        /**
         * The ID3v2 tag size is the sum of the byte length of the extended
         * header, the padding and the frames after unsynchronisation. If a
         * footer is present this equals to ('total size' - 20) bytes, otherwise
         * ('total size' - 10) bytes.
         */

        $data = $this->stream->read($header->getSize());
        if ($header->isFlagEnabled(Flag::FLAG_COMPRESSION)) {
            $data = gzuncompress($data);
        }

        if ($header->isFlagEnabled(Flag::FLAG_UNSYNCHRONISATION)) {
            $data = $this->decodeUnsynchronisation($data);
        }

        $resource = fopen('php://temp', 'r+b');
        $stream = Stream::fromResource($resource);
        $stream->write($data);
        $stream->rewind();

        $size = $stream->getSize();
        if ($header->isFlagEnabled(Flag::FLAG_EXTENDED_HEADER)) {
            $extendedHeader = $this->readExtendedHeaderFromStream($stream, $header);
            $tag->setExtendedHeader($extendedHeader);

            $size -= $extendedHeader->getSize();
        }

        if ($header->isFlagEnabled(Flag::FLAG_EXTENDED_HEADER)) {
            // TODO: Read footer from stream

            $size -= 10;
        }

        $longReader = new LongReader($stream);
        $longReader->setByteOrder(ByteOrder::BIG_ENDIAN);

        while ($size > 0) {
            if (0 === ord($stream->read(1))) {
                break;
            }

            $stream->seek(-1, SEEK_CUR);

            $frameName = $stream->read(4);
            //var_dump($frameName);

            $frameSize = $longReader->read();
            //var_dump($frameSize);

            $size -= $frameSize;
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
