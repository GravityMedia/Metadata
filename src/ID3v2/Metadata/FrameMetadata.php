<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata;

use GravityMedia\Metadata\ID3v2\Filter\SynchsafeIntegerFilter;
use GravityMedia\Metadata\ID3v2\Flag\FrameFlag;
use GravityMedia\Metadata\ID3v2\Version;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 frame metadata class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata
 */
class FrameMetadata
{
    /**
     * @var Stream
     */
    protected $stream;

    /**
     * @var int
     */
    protected $version;

    /**
     * @var SynchsafeIntegerFilter
     */
    protected $synchsafeIntegerFilter;

    /**
     * Create ID3v2 frame metadata object.
     *
     * @param Stream $stream
     * @param int $version
     */
    public function __construct(Stream $stream, $version)
    {
        $this->stream = $stream;
        $this->version = $version;
        $this->synchsafeIntegerFilter = new SynchsafeIntegerFilter();
    }

    /**
     * Read ID3v2 frame name.
     *
     * @return string
     */
    public function readName()
    {
        if (Version::VERSION_22 === $this->version) {
            return rtrim($this->stream->read(3));
        }

        return rtrim($this->stream->read(4));
    }

    /**
     * Read ID3v2 frame size.
     *
     * @return int
     */
    public function readSize()
    {
        if (Version::VERSION_22 === $this->version) {
            return $this->stream->readUInt24();
        }

        if (Version::VERSION_23 === $this->version) {
            return $this->stream->readUInt32();
        }

        return $this->synchsafeIntegerFilter->decode($this->stream->readUInt32());
    }

    /**
     * Read ID3v2 frame flags.
     *
     * @return array
     */
    public function readFlags()
    {
        if (Version::VERSION_22 === $this->version) {
            return [];
        }

        $flags = $this->stream->readUInt16();

        if (Version::VERSION_23 === $this->version) {
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
    public function readDataLength()
    {
        return $this->synchsafeIntegerFilter->decode($this->stream->readUInt32());
    }
}
