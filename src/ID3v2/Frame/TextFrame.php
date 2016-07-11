<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Frame;

use GravityMedia\Metadata\ID3v2\Frame;

/**
 * ID3v2 text frame class.
 *
 * @package GravityMedia\Metadata
 */
class TextFrame extends Frame
{
    /**
     * @var string[]
     */
    protected $text;

    /**
     * Get text.
     *
     * @return string[]
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param string[] $text
     *
     * @return $this
     */
    public function setText(array $text)
    {
        $this->text = $text;

        return $this;
    }
}
