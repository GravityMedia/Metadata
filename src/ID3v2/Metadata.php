<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\ID3v2\Tag\Header;
use GravityMedia\Metadata\ID3v2\Tag\HeaderFactory;
use GravityMedia\Metadata\MetadataInterface;
use GravityMedia\Metadata\TagInterface;
use GravityMedia\Stream\Stream;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * ID3v2 metadata
 *
 * @package GravityMedia\Metadata
 */
class Metadata implements MetadataInterface
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var \GravityMedia\Stream\StreamInterface
     */
    protected $stream;

    /**
     * @var HeaderFactory
     */
    protected $headerFactory;

    /**
     * Create ID3v2 metadata object
     *
     * @param \SplFileInfo $file
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * Get stream
     *
     * @return \GravityMedia\Stream\StreamInterface
     */
    public function getStream()
    {
        if (null === $this->stream) {
            if ($this->file->isFile()) {
                $this->stream = new Stream($this->file, 'r+b');
            } else {
                $this->stream = new Stream($this->file, 'w+b');
            }
        }

        return $this->stream;
    }

    /**
     * Get header factory
     *
     * @return HeaderFactory
     */
    public function getHeaderFactory()
    {
        if (null === $this->headerFactory) {
            $this->headerFactory = new HeaderFactory();
        }

        return $this->headerFactory;
    }

    /**
     * @inheritdoc
     */
    public function exists()
    {
        $stream = $this->getStream();
        if ($stream->getSize() < 10) {
            return false;
        }

        $stream->seek(0);

        return 'ID3' === $stream->getReader()->read(3);
    }

    /**
     * @inheritdoc
     */
    public function strip()
    {
    }

    /**
     * @inheritdoc
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $header = $this->extractHeader();
        $tag = new Tag($header);

        return $tag;
    }

    /**
     * @inheritdoc
     */
    public function write(TagInterface $tag)
    {
        if (!$tag instanceof Tag) {
            throw new Exception\InvalidArgumentException('Invalid tag argument');
        }

        $data = 'ID3';


        $stream = $this->getStream();
        if ($this->exists()) {
            $stream->seek(-128, SEEK_END);
        } else {
            $stream->seek(0, SEEK_END);
        }

        $stream->getWriter()->write($data);

        return $this;
    }

    /**
     * Extract header from stream
     *
     * @return Header
     */
    protected function extractHeader()
    {
        $stream = $this->getStream();
        $reader = $stream->getReader();

        // header
        $stream->seek(3);
        $version = ord($reader->read(1));
        $revision = ord($reader->read(1));
        $flags = ord($reader->read(1));
        $sizeReader = new BinaryReader($reader->read(4), Endian::ENDIAN_BIG);

        $header = $this->getHeaderFactory()
            ->createHeader($version, $revision, $flags, $sizeReader->readUInt32());

        // extended header
        if (!$header->getFlag(Header::FLAG_EXTENDED_HEADER)) {
            return $header;
        }

        $sizeReader = new BinaryReader($reader->read(4), Endian::ENDIAN_BIG);

        if (Header::VERSION_23 === $header->getVersion()) {
            $flagsReader = new BinaryReader($reader->read(2), Endian::ENDIAN_BIG);
            $flags = $flagsReader->readUInt32();

            return $header
                ->setFlag(Header::FLAG_CRC_DATA_PRESENT, (bool)($flags & 0x8000))
                ->setExtendedSize($sizeReader->readUInt32());
        }

        $flagsReader = new BinaryReader($reader->read(1), Endian::ENDIAN_BIG);
        $flags = $flagsReader->readUInt32();

        return $header
            ->setFlag(Header::FLAG_TAG_IS_AN_UPDATE, (bool)($flags & 0x40))
            ->setFlag(Header::FLAG_CRC_DATA_PRESENT, (bool)($flags & 0x20))
            ->setFlag(Header::FLAG_TAG_RESTRICTIONS, (bool)($flags & 0x10))
            ->setExtendedSize($sizeReader->readUInt32());
    }

    /**
     * Deunsynchronise data
     *
     * @param string $data
     *
     * @return string
     */
    public function deunsynchroniseData($data)
    {
        return str_replace("\xFF\x00", "\xFF", $data);
    }
}
