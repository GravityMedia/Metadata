<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\RuntimeException;
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
     * Create ID3v2 metadata object.
     *
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Read synchsafe unsigned 32-bit integer (long) data from the stream.
     *
     * @return int
     */
    public function readSynchsafeUInt32()
    {
        $value = $this->stream->readUInt32();

        return ($value & 0x7f) | ($value & 0x7f00) >> 1 | ($value & 0x7f0000) >> 2 | ($value & 0x7f000000) >> 3;
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
     * Read ID3v2 header version.
     *
     * @throws RuntimeException An exception is thrown on invalid versions.
     *
     * @return int
     */
    protected function readHeaderVersion()
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
     * Read ID3v2 header revision.
     *
     * @return int
     */
    protected function readHeaderRevision()
    {
        $this->stream->seek(4);

        return $this->stream->readUInt8();
    }

    /**
     * Read ID3v2 header flags.
     *
     * @param int $version
     *
     * @return array
     */
    protected function readHeaderFlags($version)
    {
        $this->stream->seek(5);

        $flags = $this->stream->readUInt8();

        if ($version === Version::VERSION_22) {
            return [
                HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                HeaderFlag::FLAG_COMPRESSION => (bool)($flags & 0x40)
            ];
        }

        if ($version === Version::VERSION_23) {
            return [
                HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                HeaderFlag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                HeaderFlag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
            ];
        }

        return [
            HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            HeaderFlag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
            HeaderFlag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20),
            HeaderFlag::FLAG_FOOTER_PRESENT => (bool)($flags & 0x10)
        ];
    }

    /**
     * Read ID3v2 header size.
     *
     * @return int
     */
    public function readHeaderSize()
    {
        $this->stream->seek(6);

        return $this->readSynchsafeUInt32();
    }

    /**
     * Read data.
     *
     * @param Header $header
     *
     * @return string
     */
    protected function readData(Header $header)
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
     * @return Stream
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
     * @return null|Tag
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $version = $this->readHeaderVersion();

        $header = new Header($version);
        $header->setRevision($this->readHeaderRevision());
        $header->setFlags($this->readHeaderFlags($version));
        $header->setSize($this->readHeaderSize());

        $data = $this->readData($header);
        $stream = $this->createDataStream($data);

        $tagReader = new TagReader($stream, $header);
        $tag = new Tag($header);
        $size = $stream->getSize();

        if ($header->isFlagEnabled(HeaderFlag::FLAG_EXTENDED_HEADER)) {
            $extendedHeader = $tagReader->readExtendedHeader();
            $tag->setExtendedHeader($extendedHeader);

            $size -= $extendedHeader->getSize();
        }

        if ($header->isFlagEnabled(HeaderFlag::FLAG_EXTENDED_HEADER)) {
            // TODO: Read footer from stream

            $size -= 10;
        }

        while ($size > 0) {
            $frame = $tagReader->readFrame();
            if (0 === $frame->getSize()) {
                break;
            }

            $tag->addFrame($frame);

            $size -= $frame->getSize();
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
