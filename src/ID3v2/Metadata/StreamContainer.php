<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata;

use GravityMedia\Stream\Stream;

/**
 * ID3v2 stream container class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata
 */
class StreamContainer
{
    /**
     * @var Stream
     */
    private $stream;

    /**
     * @var int
     */
    private $offset;

    /**
     * Create ID3v2 stream container object.
     *
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
        $this->offset = $stream->tell();
    }

    /**
     * Get stream.
     *
     * @return Stream
     */
    protected function getStream()
    {
        return $this->stream;
    }

    /**
     * Get offset.
     *
     * @return int
     */
    protected function getOffset()
    {
        return $this->offset;
    }
}
