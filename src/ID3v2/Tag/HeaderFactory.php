<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Tag;

use GravityMedia\Metadata\Exception;

/**
 * ID3v2 tag header factory
 *
 * @package GravityMedia\Metadata
 */
class HeaderFactory
{
    /**
     * Create Create ID3v2 tag header object
     *
     * @param int $version
     * @param int $revision
     * @param int $flags
     * @param int $size
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid arguments
     *
     * @return Header
     */
    public function createHeader($version, $revision, $flags, $size)
    {
        switch ($version) {
            case 2:
                $header = $this->createHeaderVersion22($flags);
                break;
            case 3:
                $header = $this->createHeaderVersion23($flags);
                break;
            case 4:
                $header = $this->createHeaderVersion24($flags);
                break;
            default:
                throw new Exception\InvalidArgumentException('Invalid version');
        }

        return $header
            ->setRevision($revision)
            ->setSize($size);
    }

    /**
     * Create Create ID3 v2.2 metadata object
     *
     * @param int $flags
     *
     * @return Header
     */
    protected function createHeaderVersion22($flags)
    {
        $header = new Header(Header::VERSION_22);

        return $header->setFlags(array(
            Header::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            Header::FLAG_COMPRESSION => (bool)($flags & 0x40)
        ));
    }

    /**
     * Create Create ID3 v2.3 metadata object
     *
     * @param int $flags
     *
     * @return Header
     */
    protected function createHeaderVersion23($flags)
    {
        $header = new Header(Header::VERSION_23);

        return $header->setFlags(array(
            Header::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            Header::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
            Header::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
        ));
    }

    /**
     * Create Create ID3 v2.4 metadata object
     *
     * @param int $flags
     *
     * @return Header
     */
    protected function createHeaderVersion24($flags)
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
