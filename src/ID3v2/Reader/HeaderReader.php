<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\Exception\RuntimeException;
use GravityMedia\Metadata\ID3v2\Enum\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Enum\Version;
use GravityMedia\Metadata\ID3v2\Header;
use GravityMedia\Metadata\ID3v2\Stream\SynchsafeInteger32Reader;
use GravityMedia\Metadata\Metadata\HeaderInterface;
use GravityMedia\Stream\Enum\ByteOrder;
use GravityMedia\Stream\Reader\Integer8Reader;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v2 header reader
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class HeaderReader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var Integer8Reader
     */
    protected $integer8Reader;

    /**
     * @var SynchsafeInteger32Reader
     */
    protected $synchsafeInteger32Reader;

    /**
     * Create ID3v2 header reader.
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Get 8-bit integer reader.
     *
     * @return Integer8Reader
     */
    public function getInteger8Reader()
    {
        if (null === $this->integer8Reader) {
            $this->integer8Reader = new Integer8Reader($this->stream);
        }

        return $this->integer8Reader;
    }

    /**
     * Get synchsafe 32-bit integer reader
     *
     * @return SynchsafeInteger32Reader
     */
    public function getSynchsafeInteger32Reader()
    {
        if (null === $this->synchsafeInteger32Reader) {
            $this->synchsafeInteger32Reader = new SynchsafeInteger32Reader($this->stream);
            $this->synchsafeInteger32Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->synchsafeInteger32Reader;
    }

    /**
     * Read ID3v2 header version.
     *
     * @throws RuntimeException An exception is thrown on invalid versions.
     *
     * @return int
     */
    protected function readVersion()
    {
        $this->stream->seek(3);
        switch ($this->getInteger8Reader()->read()) {
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
    protected function readRevision()
    {
        $this->stream->seek(4);

        return $this->getInteger8Reader()->read();
    }

    /**
     * Read ID3v2 header flags.
     *
     * @param int $version
     *
     * @return array
     */
    protected function readFlags($version)
    {
        $this->stream->seek(5);
        $flags = $this->getInteger8Reader()->read();

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
    public function readSize()
    {
        $this->stream->seek(6);

        return $this->getSynchsafeInteger32Reader()->read();
    }

    /**
     * Read ID3v2 header.
     *
     * @return HeaderInterface
     */
    public function read()
    {
        $version = $this->readVersion();

        $header = new Header($version);
        return $header
            ->setRevision($this->readRevision())
            ->setFlags($this->readFlags($version))
            ->setSize($this->readSize());
    }
}
