<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata\Frame;

use GravityMedia\Metadata\ID3v2\Encoding;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 text information frame class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata\Frame
 */
class TextInformationFrame
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
     * Create ID3v2 header metadata object.
     *
     * @param Stream $stream
     * @param int $version
     */
    public function __construct(Stream $stream, $version)
    {
        $this->stream = $stream;
        $this->version = $version;
    }

    /**
     * Read text encoding.
     *
     * @return int
     */
    public function readTextEncoding()
    {
        $this->stream->seek(0);

        return $this->stream->readUInt8();
    }

    /**
     * Read information.
     *
     * @param int $textEncoding
     *
     * @return string
     */
    public function readInformation($textEncoding)
    {
        $this->stream->seek(1);

        $information = $this->stream->read($this->stream->getSize() - 1);

        switch ($textEncoding) {
            case Encoding::ISO_8859_1:
                return iconv('ISO-8859-1', 'UTF-8', $information);
            case Encoding::UTF_16:
                return iconv('UTF-16', 'UTF-8', $information);
            case Encoding::UTF_16BE:
                return iconv('UTF-16BE', 'UTF-8', $information);
            case Encoding::UTF_16LE:
                return iconv('UTF-16LE', 'UTF-8', $information);
        }

        return $information;
    }
}
