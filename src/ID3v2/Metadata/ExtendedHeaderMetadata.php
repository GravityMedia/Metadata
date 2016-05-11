<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata;

use GravityMedia\Metadata\ID3v2\Filter\SynchsafeIntegerFilter;
use GravityMedia\Metadata\ID3v2\Flag\ExtendedHeaderFlag;
use GravityMedia\Metadata\ID3v2\Version;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 extended header metadata class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata
 */
class ExtendedHeaderMetadata
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
     * Create ID3v2 extended header metadata object.
     *
     * @param Stream $stream
     * @param int    $version
     */
    public function __construct(Stream $stream, $version)
    {
        $this->stream = $stream;
        $this->version = $version;
        $this->synchsafeIntegerFilter = new SynchsafeIntegerFilter();
    }

    /**
     * Read ID3v2 extended header size.
     *
     * @return int
     */
    public function readSize()
    {
        if (Version::VERSION_23 === $this->version) {
            return $this->stream->readUInt32();
        }

        return $this->synchsafeIntegerFilter->decode($this->stream->readUInt32());
    }

    /**
     * Read ID3v2 extended header flags.
     *
     * @return array
     */
    public function readFlags()
    {
        if (Version::VERSION_23 === $this->version) {
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
    public function readPadding()
    {
        return $this->stream->readUInt32();
    }

    /**
     * Read ID3v2 extended header CRC-32 data.
     *
     * @return int
     */
    public function readCrc32()
    {
        if (Version::VERSION_23 === $this->version) {
            return $this->stream->readUInt32();
        }

        $this->stream->seek(1, SEEK_CUR);

        return $this->stream->readUInt8() * 0x10000000 +
        $this->synchsafeIntegerFilter->decode($this->stream->readUInt32());
    }

    /**
     * Read ID3v2 extended header restrictions.
     *
     * @return int
     */
    public function readRestrictions()
    {
        $this->stream->seek(1, SEEK_CUR);

        return $this->stream->readUInt8();
    }
}
