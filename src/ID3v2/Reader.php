<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Stream\Stream;

/**
 * ID3v2 reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class Reader extends StreamContainer
{
    /**
     * @var int
     */
    private $version;

    /**
     * Create ID3v2 abstract frame reader object.
     *
     * @param Stream $stream
     * @param int    $version
     */
    public function __construct(Stream $stream, $version)
    {
        parent::__construct($stream);

        $this->version = $version;
    }

    /**
     * Get version.
     *
     * @return int
     */
    protected function getVersion()
    {
        return $this->version;
    }
}
