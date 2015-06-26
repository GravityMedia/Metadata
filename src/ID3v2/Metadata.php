<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\MetadataInterface;
use GravityMedia\Metadata\TagInterface;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 metadata
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Metadata implements MetadataInterface
{
    const FLAG_UNSYNCHRONISATION = 0;
    const FLAG_COMPRESSION = 1;
    const FLAG_EXTENDED_HEADER = 2;
    const FLAG_EXPERIMENTAL_INDICATOR = 3;
    const FLAG_FOOTER_PRESENT = 4;

    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var \GravityMedia\Stream\StreamInterface
     */
    protected $stream;

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
     * Return version
     *
     * @param int $major
     * @param int $minor
     *
     * @return null|string
     */
    protected function version($major, $minor)
    {
        switch ($major) {
            case 2:
                return Tag::VERSION_22;
            case 3:
                return Tag::VERSION_23;
            case 4:
                return Tag::VERSION_24;
        }
        return null;
    }

    /**
     * Return flags
     *
     * @param string $version
     * @param int    $flags
     *
     * @return array
     */
    public function flags($version, $flags)
    {
        switch ($version) {
            case Tag::VERSION_22:
                return array(
                    self::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    self::FLAG_COMPRESSION => (bool)($flags & 0x40)
                );
            case Tag::VERSION_23:
                return array(
                    self::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    self::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                    self::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
                );
            case Tag::VERSION_24:
                return array(
                    self::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                    self::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                    self::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20),
                    self::FLAG_FOOTER_PRESENT => (bool)($flags & 0x10)
                );
        }
        return array();
    }

    /**
     * Return size
     *
     * @param string $data
     *
     * @return int
     */
    protected function size($data)
    {
        $unpacked = unpack('N', $data);
        $value = current($unpacked);
        while ($value >= 0x80000000) {
            $value -= 0x100000000;
        }
        return $value;
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
        if ('ID3' === $stream->getReader()->read(3)) {
            return true;
        }

        return false;
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

        $stream = $this->getStream();
        $reader = $stream->getReader();

        // header
        $stream->seek(3);
        $version = $this->version(ord($reader->read(1)), ord($reader->read(1)));
        if (null === $version) {
            return null;
        }

        $flags = $this->flags($version, ord($reader->read(1)));
        if (empty($flags)) {
            return null;
        }

        $size = $this->size($reader->read(4));
        if ($size < 1) {
            return null;
        }

        // extended header
        if ($version > Tag::VERSION_22 && $flags[self::FLAG_EXTENDED_HEADER]) {
            $extendedHeaderSize = $this->size($reader->read(4));
            if ($version === Tag::VERSION_23) {
                // ToDo
            } elseif ($version === Tag::VERSION_24) {
                // ToDo
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function write(TagInterface $tag)
    {
    }
}
