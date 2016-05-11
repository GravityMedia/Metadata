<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata;

use GravityMedia\Metadata\ID3v2\Filter\SynchsafeIntegerFilter;
use GravityMedia\Metadata\ID3v2\Flag\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Version;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 header metadata class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata
 */
class HeaderMetadata
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
     * Create ID3v2 header metadata object.
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
     * Read ID3v2 header size.
     *
     * @return int
     */
    public function readSize()
    {
        $this->stream->seek(6);

        return $this->synchsafeIntegerFilter->decode($this->stream->readUInt32());
    }

    /**
     * Read ID3v2 header flags.
     *
     * @return array
     */
    public function readFlags()
    {
        $this->stream->seek(5);

        $flags = $this->stream->readUInt8();

        if (Version::VERSION_22 === $this->version) {
            return [
                HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                HeaderFlag::FLAG_COMPRESSION => (bool)($flags & 0x40)
            ];
        }

        if (Version::VERSION_23 === $this->version) {
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
}
