<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Stream\InputStream;
use GravityMedia\Stream\OutputStream;

/**
 * ID3v2 metadata
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class Metadata
{
    const FLAG_UNSYNCHRONISATION = 0;
    const FLAG_COMPRESSION = 1;
    const FLAG_EXTENDED_HEADER = 2;
    const FLAG_EXPERIMENTAL_INDICATOR = 3;
    const FLAG_FOOTER_PRESENT = 4;

    /**
     * Input stream
     *
     * @var InputStream
     */
    protected $inputStream;

    /**
     * Output stream
     *
     * @var OutputStream
     */
    protected $outputStream;

    /**
     * Create metadata
     *
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->inputStream = new InputStream($resource);
        $this->outputStream = new OutputStream($resource);
    }

    /**
     * Get input stream
     *
     * @return InputStream
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * Get output stream
     *
     * @return OutputStream
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * Trim data
     *
     * @param string $data
     *
     * @return string
     */
    protected function trimData($data)
    {
        return trim(substr($data, 0, strcspn($data, "\x00")));
    }

    /**
     * Pad data
     *
     * @param string $data
     * @param int $length
     * @param int $type
     *
     * @return string
     */
    protected function padData($data, $length, $type)
    {
        return str_pad(trim(substr($data, 0, $length)), $length, "\x00", $type);
    }

    /**
     * De-unsynchronize data
     *
     * @param string $data
     *
     * @return string
     */
    protected function deUnsynchronise($data) {
        return str_replace("\xFF\x00", "\xFF", $data);
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
     * @param int $flags
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
     * Returns true when metadata exists
     *
     * @return bool
     */
    public function exists()
    {
        $stream = $this->getInputStream();

        if ($stream->stats(true)->getSize() >= 10) {
            $stream->seek(0);
            if ('ID3' === $stream->read(3)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract metadata
     *
     * @return Tag|null
     */
    public function extract()
    {
        if (!$this->exists()) {
            return null;
        }

        $stream = $this->getInputStream();

        // header
        $stream->seek(3);
        $version = $this->version(ord($stream->read(1)), ord($stream->read(1)));
        if (null === $version) {
            return null;
        }

        $flags = $this->flags($version, ord($stream->read(1)));
        if (empty($flags)) {
            return null;
        }

        $size = $this->size($stream->read(4));
        if ($size < 1) {
            return null;
        }

        // extended header
        if ($version > Tag::VERSION_22 && $flags[self::FLAG_EXTENDED_HEADER]) {
            $extendedHeaderSize = $this->size($stream->read(4));
            if ($version === Tag::VERSION_23) {

            } elseif ($version === Tag::VERSION_24) {

            }
        }


        $tag = new Tag($version);
        $stream->seek(-125, SEEK_END);
        $tag->setTitle($this->trimData($stream->read(30)))
            ->setArtist($this->trimData($stream->read(30)))
            ->setAlbum($this->trimData($stream->read(30)))
            ->setYear($this->trimData($stream->read(4)));

        if (Tag::VERSION_11 === $version) {
            $tag->setComment($this->trimData($stream->read(28)));
            $stream->seek(1, SEEK_CUR);
            $tag->setTrack(ord($stream->read(1)));
        } else {
            $tag->setComment($this->trimData($stream->read(30)));
        }

        $genreId = ord($stream->read(1));
        if (isset(Tag::$genres[$genreId])) {
            $tag->setGenre(Tag::$genres[$genreId]);
        }

        return $tag;
    }

    /**
     * Strip metadata
     *
     * @return Metadata
     */
    public function strip()
    {
        if (!$this->exists()) {
            return $this;
        }

        $stream = $this->getOutputStream();
        $stream->truncate($stream->stats(true)->getSize() - 128);

        return $this;
    }

    /**
     * Hydrate metadata
     *
     * @param Tag $tag
     *
     * @return Metadata
     */
    public function hydrate(Tag $tag)
    {
        $data = 'TAG';
        $data .= $this->padData($tag->getTitle(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getArtist(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getAlbum(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getYear(), 4, STR_PAD_LEFT);

        if (Tag::VERSION_11 === $tag->getVersion()) {
            $data .= $this->padData($tag->getComment(), 28, STR_PAD_RIGHT);
            $data .= "\x00";
            $data .= chr($tag->getTrack());
        } else {
            $data .= $this->padData($tag->getComment(), 30, STR_PAD_RIGHT);
        }

        $genreId = array_search($tag->getGenre(), Tag::$genres);
        $data .= chr(false === $genreId ? 255 : $genreId);

        $stream = $this->getOutputStream();

        if ($this->exists()) {
            $stream->seek(-128, SEEK_END);
        } else {
            $stream->seek(0, SEEK_END);
        }

        $stream->write($data);

        return $this;
    }

    /**
     * Create instance from file
     *
     * @param string $file
     *
     * @return Metadata
     */
    public static function fromFile($file)
    {
        return new static(fopen($file, 'wb+'));
    }
}
