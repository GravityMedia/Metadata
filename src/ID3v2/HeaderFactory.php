<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception;
use GravityMedia\Stream\Stream;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * ID3v2 header factory
 *
 * @package GravityMedia\Metadata
 */
class HeaderFactory
{
    /**
     * Create ID3v2 header object
     *
     * @param string $data The byte vector which represents the header
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid arguments
     *
     * @return Header
     */
    public function createHeader($data)
    {
        $stream = new Stream('php://temp', 'w+b');
        $reader = $stream->getReader();
        $writer = $stream->getWriter();

        $writer->write($data);
        $stream->rewind();

        if (10 !== $stream->getSize() || 'ID3' !== $reader->read(3)) {
            throw new Exception\InvalidArgumentException('Invalid data argument');
        }

        $version = ord($reader->read(1));
        $revision = ord($reader->read(1));
        $flags = ord($reader->read(1));
        $size = new BinaryReader($reader->read(4), Endian::ENDIAN_BIG);

        switch ($version) {
            case 2:
                $header = $this->createHeaderVersion2($flags);
                break;
            case 3:
                $header = $this->createHeaderVersion3($flags);
                break;
            case 4:
                $header = $this->createHeaderVersion4($flags);
                break;
            default:
                throw new Exception\RuntimeException('Invalid version');
        }

        return $header
            ->setRevision($revision)
            ->setSize($size->readUInt32());
    }

    /**
     * Create ID3v2 version 2 header object
     *
     * @param int $flags
     *
     * @return Header
     */
    protected function createHeaderVersion2($flags)
    {
        $header = new Header(Header::VERSION_22);

        return $header->setFlags(array(
            Header::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            Header::FLAG_COMPRESSION => (bool)($flags & 0x40)
        ));
    }

    /**
     * Create ID3v2 version 3 header object
     *
     * @param int $flags
     *
     * @return Header
     */
    protected function createHeaderVersion3($flags)
    {
        $header = new Header(Header::VERSION_23);

        return $header->setFlags(array(
            Header::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            Header::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
            Header::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
        ));
    }

    /**
     * Create ID3v2 version 4 header object
     *
     * @param int $flags
     *
     * @return Header
     */
    protected function createHeaderVersion4($flags)
    {
        $header = new Header(Header::VERSION_24);

        return $header->setFlags(array(
            Header::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            Header::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
            Header::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20),
            Header::FLAG_FOOTER_PRESENT => (bool)($flags & 0x10)
        ));
    }
}
