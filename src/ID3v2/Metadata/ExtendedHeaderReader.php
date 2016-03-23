<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata;

use GravityMedia\Metadata\ID3v2\Enum\ExtendedHeaderFlag;
use GravityMedia\Metadata\ID3v2\Enum\Version;
use GravityMedia\Metadata\ID3v2\ExtendedHeader;
use GravityMedia\Metadata\ID3v2\Stream\SynchsafeInteger32Reader;
use GravityMedia\Metadata\Metadata\ExtendedHeaderInterface;
use GravityMedia\Metadata\Metadata\HeaderInterface;
use GravityMedia\Stream\Enum\ByteOrder;
use GravityMedia\Stream\Reader\Integer16Reader;
use GravityMedia\Stream\Reader\Integer32Reader;
use GravityMedia\Stream\Reader\Integer8Reader;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v2 extended header reader
 *
 * @package GravityMedia\Metadata\ID3v2\StreamReader
 */
class ExtendedHeaderReader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var HeaderInterface
     */
    protected $header;

    /**
     * @var Integer8Reader
     */
    protected $integer8Reader;

    /**
     * @var Integer16Reader
     */
    protected $integer16Reader;

    /**
     * @var Integer32Reader
     */
    protected $integer32Reader;

    /**
     * @var SynchsafeInteger32Reader
     */
    protected $synchsafeInteger32Reader;

    /**
     * Create ID3v2 extended header reader.
     *
     * @param StreamInterface $stream
     * @param HeaderInterface $header
     */
    public function __construct(StreamInterface $stream, HeaderInterface $header)
    {
        $this->stream = $stream;
        $this->header = $header;
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
     * Get 16-bit integer reader
     *
     * @return Integer16Reader
     */
    public function getInteger16Reader()
    {
        if (null === $this->integer16Reader) {
            $this->integer16Reader = new Integer16Reader($this->stream);
            $this->integer16Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->integer16Reader;
    }

    /**
     * Get 32-bit integer reader
     *
     * @return Integer32Reader
     */
    public function getInteger32Reader()
    {
        if (null === $this->integer32Reader) {
            $this->integer32Reader = new Integer32Reader($this->stream);
            $this->integer32Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->integer32Reader;
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
     * Read ID3v2 extended header size.
     *
     * @return int
     */
    protected function readSize()
    {
        if (Version::VERSION_23 === $this->header->getVersion()) {
            return $this->getInteger32Reader()->read();
        }

        return $this->getSynchsafeInteger32Reader()->read();
    }

    /**
     * Read ID3v2 extended header flags.
     *
     * @return array
     */
    protected function readFlags()
    {
        if (Version::VERSION_23 === $this->header->getVersion()) {
            $flags = $this->getInteger16Reader()->read();

            return [
                ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x8000)
            ];
        }

        $this->stream->seek(1, SEEK_CUR);

        $flags = $this->getInteger8Reader()->read();

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
    protected function readPadding()
    {
        return $this->getInteger32Reader()->read();
    }

    /**
     * Read ID3v2 extended header CRC-32 data.
     *
     * @return int
     */
    protected function readCrc32()
    {
        if (Version::VERSION_23 === $this->header->getVersion()) {
            return $this->getInteger32Reader()->read();
        }

        $this->stream->seek(1, SEEK_CUR);

        return $this->getInteger8Reader()->read() * (0xfffffff + 1) + $this->getSynchsafeInteger32Reader()->read();
    }

    /**
     * Read ID3v2 extended header restrictions.
     *
     * @return int
     */
    protected function readRestrictions()
    {
        $this->stream->seek(1, SEEK_CUR);

        return $this->getInteger8Reader()->read();
    }

    /**
     * Read ID3v2 extended header.
     *
     * @return ExtendedHeaderInterface
     */
    public function read()
    {
        $extendedHeader = new ExtendedHeader();
        $extendedHeader
            ->setSize($this->readSize())
            ->setFlags($this->readFlags());

        // Only in ID3v2.3
        if (Version::VERSION_23 === $this->header->getVersion()) {
            $extendedHeader->setPadding($this->readPadding());
        }

        // Only in ID3v2.3
        if ($extendedHeader->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE)) {
            $this->stream->seek(1, SEEK_CUR);
        }

        if ($extendedHeader->isFlagEnabled(ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT)) {
            $extendedHeader->setCrc32($this->readCrc32());
        }

        // Only in ID3v2.4
        if ($extendedHeader->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_RESTRICTIONS)) {
            $extendedHeader->setRestrictions($this->readRestrictions());
        }

        return $extendedHeader;
    }
}
