<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Writer;

use GravityMedia\Metadata\ID3v2\StreamContainer;

/**
 * ID3v2 frame header writer class.
 *
 * @package GravityMedia\Metadata\ID3v2\Writer
 */
class FrameHeaderWriter extends StreamContainer
{
    /**
     * @var string
     */
    private $name;

    /**
     * Set ID3v2 frame name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Write ID3v2 frame data data.
     *
     * @return $this
     */
    public function write()
    {
        $this->getStream()->seek($this->getOffset());

        $this->getStream()->write($this->name);

        return $this;
    }
}
