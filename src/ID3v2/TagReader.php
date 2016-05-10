<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Stream\Stream;

/**
 * ID3v2 tag reader class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class TagReader
{
    /**
     * @var Stream
     */
    protected $stream;

    /**
     * @var Header
     */
    protected $header;

    /**
     * Create ID3v2 tag reader object.
     *
     * @param Stream $stream
     * @param Header $header
     */
    public function __construct(Stream $stream, Header $header)
    {
        $this->stream = $stream;
        $this->header = $header;
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
     * Read ID3v2 extended header size.
     *
     * @return int
     */
    protected function readExtendedHeaderSize()
    {
        if (Version::VERSION_23 === $this->header->getVersion()) {
            return $this->stream->readUInt32();
        }

        return $this->readSynchsafeUInt32();
    }

    /**
     * Read ID3v2 extended header flags.
     *
     * @return array
     */
    protected function readExtendedHeaderFlags()
    {
        if (Version::VERSION_23 === $this->header->getVersion()) {
            $flags = $this->stream->readUInt16();

            return [
                ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x8000)
            ];
        }

        $this->stream->seek(1, SEEK_CUR);

        $flags = $this->stream->readUInt8();

        return [
            ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE => (bool)($flags & 0x40),
            ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x20),
            ExtendedHeaderFlag::FLAG_TAG_RESTRICTIONS => (bool)($flags & 0x10)
        ];
    }

    /**
     * Read ID3v2 extended header padding.
     *
     * @return int
     */
    protected function readExtendedHeaderPadding()
    {
        return $this->stream->readUInt32();
    }

    /**
     * Read ID3v2 extended header CRC-32 data.
     *
     * @return int
     */
    protected function readExtendedHeaderCrc32()
    {
        if (Version::VERSION_23 === $this->header->getVersion()) {
            return $this->stream->readUInt32();
        }

        $this->stream->seek(1, SEEK_CUR);

        return $this->stream->readUInt8() * (0xfffffff + 1) + $this->readSynchsafeUInt32();
    }

    /**
     * Read ID3v2 extended header restrictions.
     *
     * @return int
     */
    protected function readExtendedHeaderRestrictions()
    {
        $this->stream->seek(1, SEEK_CUR);

        return $this->stream->readUInt8();
    }

    /**
     * Read ID3v2 extended header.
     *
     * @return ExtendedHeader
     */
    public function readExtendedHeader()
    {
        $extendedHeader = new ExtendedHeader();
        $extendedHeader
            ->setSize($this->readExtendedHeaderSize())
            ->setFlags($this->readExtendedHeaderFlags());

        // Only in ID3v2.3
        if (Version::VERSION_23 === $this->header->getVersion()) {
            $extendedHeader->setPadding($this->readExtendedHeaderPadding());
        }

        // Only in ID3v2.3
        if ($extendedHeader->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE)) {
            $this->stream->seek(1, SEEK_CUR);
        }

        if ($extendedHeader->isFlagEnabled(ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT)) {
            $extendedHeader->setCrc32($this->readExtendedHeaderCrc32());
        }

        // Only in ID3v2.4
        if ($extendedHeader->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_RESTRICTIONS)) {
            $extendedHeader->setRestrictions($this->readExtendedHeaderRestrictions());
        }

        return $extendedHeader;
    }

    /**
     * Read ID3v2 frame name.
     *
     * @return string
     */
    protected function readFrameName()
    {
        if (Version::VERSION_22 === $this->header->getVersion()) {
            return rtrim($this->stream->read(3));
        }

        return rtrim($this->stream->read(4));
    }

    /**
     * Read ID3v2 frame size.
     *
     * @return int
     */
    protected function readFrameSize()
    {
        if (Version::VERSION_22 === $this->header->getVersion()) {
            return $this->stream->readUInt24();
        }

        if (Version::VERSION_23 === $this->header->getVersion()) {
            return $this->stream->readUInt32();
        }

        return $this->readSynchsafeUInt32();
    }

    /**
     * Read ID3v2 frame flags.
     *
     * @return array
     */
    protected function readFrameFlags()
    {
        $flags = $this->stream->readUInt16();

        if (Version::VERSION_23 === $this->header->getVersion()) {
            return [
                FrameFlag::FLAG_TAG_ALTER_PRESERVATION => (bool)($flags & 0x8000),
                FrameFlag::FLAG_FILE_ALTER_PRESERVATION => (bool)($flags & 0x4000),
                FrameFlag::FLAG_READ_ONLY => (bool)($flags & 0x2000),
                FrameFlag::FLAG_COMPRESSION => (bool)($flags & 0x0080),
                FrameFlag::FLAG_ENCRYPTION => (bool)($flags & 0x0040),
                FrameFlag::FLAG_GROUPING_IDENTITY => (bool)($flags & 0x0020),
            ];
        }

        return [
            FrameFlag::FLAG_TAG_ALTER_PRESERVATION => (bool)($flags & 0x4000),
            FrameFlag::FLAG_FILE_ALTER_PRESERVATION => (bool)($flags & 0x2000),
            FrameFlag::FLAG_READ_ONLY => (bool)($flags & 0x1000),
            FrameFlag::FLAG_GROUPING_IDENTITY => (bool)($flags & 0x0040),
            FrameFlag::FLAG_COMPRESSION => (bool)($flags & 0x0008),
            FrameFlag::FLAG_ENCRYPTION => (bool)($flags & 0x0004),
            FrameFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x0002),
            FrameFlag::FLAG_DATA_LENGT_INDICATOR => (bool)($flags & 0x0001),
        ];
    }

    /**
     * Read ID3v2 frame data length.
     *
     * @return int
     */
    public function readFrameDataLength()
    {
        return $this->readSynchsafeUInt32();
    }

    /**
     * Read ID3v2 frame.
     *
     * @return Frame
     */
    public function readFrame()
    {
        $name = $this->readFrameName();
        $size = $this->readFrameSize();

        $frame = new Frame();
        $frame
            ->setName($name)
            ->setSize($size);

        // Return empty frame
        if (0 === $size) {
            return $frame;
        }

        // Only in ID3v2.3 and ID3v2.4
        if (Version::VERSION_22 !== $this->header->getVersion()) {
            $frame->setFlags($this->readFrameFlags());
        }

        // Only in ID3v2.4
        if ($frame->isFlagEnabled(FrameFlag::FLAG_DATA_LENGT_INDICATOR)) {
            $frame->setDataLength($this->readFrameDataLength());

            $size -= 4;
        }

        $data = $this->stream->read($size);
        if ($frame->isFlagEnabled(FrameFlag::FLAG_COMPRESSION)) {
            $data = gzuncompress($data);
        }

        // Only in ID3v2.4
        if ($frame->isFlagEnabled(FrameFlag::FLAG_UNSYNCHRONISATION)) {
            $data = Unsynchronisation::decode($data);
        }

        return $frame
            ->setData($data);
    }
}
