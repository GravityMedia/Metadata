<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata\Frame;

use GravityMedia\Stream\Stream;

/**
 * ID3v2 text information frame class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata\Frame
 */
class TextInformationFrame
{
    /**
     * The ISO-8859-1 encoding.
     */
    const ISO88591 = 0;

    /**
     * The UTF-16 Unicode encoding with BOM.
     * */
    const UTF16 = 1;

    /**
     * The UTF-16BE Unicode encoding without BOM.
     */
    const UTF16BE = 2;

    /**
     * The UTF-8 Unicode encoding.
     */
    const UTF8 = 3;

    /**
     * The UTF-16LE Unicode encoding without BOM.
     * */
    const UTF16LE = 4;

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
     * @param int    $version
     */
    public function __construct(Stream $stream, $version)
    {
        $this->stream = $stream;
        $this->version = $version;
    }

    public function readTextEncoding()
    {
        return $this->stream->readUInt8();
    }

    public function readInformation($textEncoding)
    {
        $this->stream->seek(1);

        $information = $this->stream->read($this->stream->getSize() - 1);

        switch ($textEncoding) {
            case self::ISO88591:
                return iconv('ISO-8859-1', 'UTF-8', $information);
            case self::UTF16:
                return iconv('UTF-16', 'UTF-8', $information);
        }

        return $information;
    }
}
