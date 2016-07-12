<?php
/**
 * This file is part of the Metadata project.
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
    protected $texts;

    /**
     * Get texts.
     *
     * @return string[]
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * Set texts.
     *
     * @param string[] $texts
     *
     * @return $this
     */
    public function setTexts(array $texts)
    {
        $this->texts = $texts;

        return $this;
    }
}
