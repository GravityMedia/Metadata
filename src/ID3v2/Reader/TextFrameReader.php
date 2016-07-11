<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Filter\CharsetFilter;
use GravityMedia\Metadata\ID3v2\StreamContainer;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 text frame reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class TextFrameReader extends StreamContainer
{
    /**
     * @var CharsetFilter
     */
    private $charsetFilter;

    /**
     * @var int
     */
    private $encoding;

    /**
     * @var string[]
     */
    private $text;

    /**
     * {@inheritdoc}
     */
    public function __construct(Stream $stream)
    {
        parent::__construct($stream);

        $this->charsetFilter = new CharsetFilter();
    }

    /**
     * Read encoding.
     *
     * @return int
     */
    protected function readEncoding()
    {
        $this->getStream()->seek($this->getOffset());

        return $this->getStream()->readUInt8();
    }

    /**
     * Get encoding.
     *
     * @return int
     */
    public function getEncoding()
    {
        if (null === $this->encoding) {
            $this->encoding = $this->readEncoding();
        }

        return $this->encoding;
    }

    /**
     * Read text.
     *
     * @return string[]
     */
    protected function readText()
    {
        $this->getStream()->seek($this->getOffset() + 1);
        $text = $this->getStream()->read($this->getStream()->getSize() - 1);

        return explode("\x00", $this->charsetFilter->decode($text, $this->getEncoding()));
    }

    /**
     * Get text.
     *
     * @return string[]
     */
    public function getText()
    {
        if (null === $this->text) {
            $this->text = $this->readText();
        }

        return $this->text;
    }
}
