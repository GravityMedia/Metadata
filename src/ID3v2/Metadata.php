<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception\RuntimeException;
use GravityMedia\Metadata\ID3v2\Enum\Flag;
use GravityMedia\Metadata\ID3v2\Enum\Version;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Metadata\Metadata\TagInterface;
use GravityMedia\Stream\StreamInterface;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * ID3v2 metadata
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Metadata implements MetadataInterface
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create stream provider object from stream.
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function strip()
    {
        return $this;
    }

    /**
     * Decode the given 28-bit synchsafe integer to regular 32-bit integer.
     *
     * @param int $data
     *
     * @return int
     */
    protected function decodeSynchsafe32($data)
    {
        return ($data & 0x7f) | ($data & 0x7f00) >> 1 |
        ($data & 0x7f0000) >> 2 | ($data & 0x7f000000) >> 3;
    }

    /**
     * Decode unsynchronisation.
     *
     * @param string $data
     *
     * @return string
     */
    protected function decodeUnsynchronisation($data)
    {
        return preg_replace('/\xff\x00\x00/', "\xff\x00", preg_replace('/\xff\x00(?=[\xe0-\xff])/', "\xff", $data));
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        if (!$this->exists()) {
            return null;
        }

        $this->stream->seek(3);

        switch (ord($this->stream->read(1))) {
            case 2:
                $version = Version::VERSION_22;
                break;
            case 3:
                $version = Version::VERSION_23;
                break;
            case 4:
                $version = Version::VERSION_24;
                break;
            default:
                throw new RuntimeException('Invalid version.');
        }

        $header = new Header($version);
        $header->setRevision(ord($this->stream->read(1)));

        $flags = ord($this->stream->read(1));
        switch ($version) {
            case Version::VERSION_22:
                $header->setFlags([
                    Flag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    Flag::FLAG_COMPRESSION => (bool)($flags & 0x40)
                ]);
                break;
            case Version::VERSION_23:
                $header->setFlags([
                    Flag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    Flag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                    Flag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
                ]);
                break;
            case Version::VERSION_24:
                $header->setFlags([
                    Flag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    Flag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                    Flag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20),
                    Flag::FLAG_FOOTER_PRESENT => (bool)($flags & 0x10)
                ]);
                break;
        }

        $size = new BinaryReader($this->stream->read(4), Endian::ENDIAN_BIG);
        $header->setSize($this->decodeSynchsafe32($size->readUInt32()));

        $tag = new Tag($header);

        if ($header->isFlagEnabled(Flag::FLAG_EXTENDED_HEADER)) {
            $extendedHeader = new ExtendedHeader();

            // TODO: Read extended header.

            $tag->setExtendedHeader($extendedHeader);
        }

        /*
        $size = $header->getSize();
        while ($size > 0) {
            switch ($version) {
                case Version::VERSION_22:
                    $frameName = $this->stream->read(3);
                    var_dump($frameName);

                    $frameSize = new BinaryReader($this->stream->read(3), Endian::ENDIAN_BIG);
                    var_dump($frameSize->readUInt32());

                    exit;
                    break;
            }
        }

        $data = $this->stream->read($header->getSize());
        var_dump($data);
        */

        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function write(TagInterface $tag)
    {
        return $this;
    }
}
