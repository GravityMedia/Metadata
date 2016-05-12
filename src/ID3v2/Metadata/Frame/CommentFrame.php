<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata\Frame;

use GravityMedia\Stream\Stream;

/**
 * ID3v2 comment frame class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata\Frame
 */
class CommentFrame extends TextFrame
{
    /**
     * @var string
     */
    private $language;

    /**
     * Create ID3v2 comment frame object.
     *
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        parent::__construct($stream, 4);
    }

    /**
     * Read language.
     *
     * @return string
     */
    public function readLanguage()
    {
        $this->getStream()->seek($this->getOffset() + 1);
        $language = strtoupper(trim($this->getStream()->read(3), "\x00"));

        if ('XXX' === $language || strlen($language) !== 3) {
            $language = 'UND';
        }

        return $language;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage()
    {
        if (null === $this->language) {
            $this->language = $this->readLanguage();
        }

        return $this->language;
    }
}
