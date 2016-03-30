<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1\Writer;

use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\ID3v1\TagInterface;
use GravityMedia\Metadata\Metadata\MetadataInterface;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v1 tag writer.
 *
 * @package GravityMedia\Metadata\ID3v1\Writer
 */
class TagWriter
{
    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create ID3v1 tag writer object.
     *
     * @param MetadataInterface $metadata
     * @param StreamInterface $stream
     */
    public function __construct(MetadataInterface $metadata, StreamInterface $stream)
    {
        $this->metadata = $metadata;
        $this->stream = $stream;
    }

    /**
     * Pad data.
     *
     * @param string $data The data to pad
     * @param int $length The final length
     * @param int $type The type of padding
     *
     * @return string
     */
    protected function padData($data, $length, $type)
    {
        return str_pad(trim(substr($data, 0, $length)), $length, "\x00", $type);
    }

    /**
     * Write ID3v1 tag.
     *
     * @param TagInterface $tag
     *
     * @return $this
     */
    public function write(TagInterface $tag)
    {
        $offset = 0;
        if ($this->metadata->exists()) {
            $offset = -128;
        }

        $this->stream->seek($offset, SEEK_END);

        $data = 'TAG';
        $data .= $this->padData($tag->getTitle(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getArtist(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getAlbum(), 30, STR_PAD_RIGHT);
        $data .= $this->padData($tag->getYear(), 4, STR_PAD_LEFT);

        if (Version::VERSION_11 === $tag->getVersion()) {
            $data .= $this->padData($tag->getComment(), 28, STR_PAD_RIGHT);
            $data .= "\x00";
            $data .= chr($tag->getTrack());
        } else {
            $data .= $this->padData($tag->getComment(), 30, STR_PAD_RIGHT);
        }

        $data .= chr($tag->getGenre());

        $this->stream->write($data);

        return $this;
    }
}
