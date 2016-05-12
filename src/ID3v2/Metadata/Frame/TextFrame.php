<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata\Frame;

use GravityMedia\Metadata\ID3v2\Encoding;
use GravityMedia\Metadata\ID3v2\Metadata\StreamContainer;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 text frame class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata\Frame
 */
class TextFrame extends StreamContainer
{
    /**
     * String representations of encodings.
     *
     * @var string[]
     */
    protected static $encodings = [
        Encoding::ISO_8859_1 => 'ISO-8859-1',
        Encoding::UTF_8 => 'UTF-8',
        Encoding::UTF_16 => 'UTF-16',
        Encoding::UTF_16BE => 'UTF-16BE',
        Encoding::UTF_16LE => 'UTF-16LE'
    ];

    /**
     * @var int
     */
    private $textOffset;

    /**
     * @var int
     */
    private $encoding;

    /**
     * @var string[]
     */
    private $texts;

    /**
     * Create ID3v2 text frame object.
     *
     * @param Stream $stream
     * @param int    $textOffset
     */
    public function __construct(Stream $stream, $textOffset = 1)
    {
        parent::__construct($stream);

        $this->textOffset = $textOffset;
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
     * Read texts.
     *
     * @return string
     */
    protected function readTexts()
    {
        $this->getStream()->seek($this->getOffset() + $this->textOffset);
        $text = $this->getStream()->read($this->getStream()->getSize() - $this->textOffset);

        $encoding = $this->getEncoding();
        if (isset(static::$encodings[$encoding])) {
            $text = iconv(static::$encodings[$encoding], static::$encodings[Encoding::UTF_8], $text);
        }

        return explode("\x00", $text);
    }

    /**
     * Get text.
     *
     * @param int $index
     *
     * @return null|string
     */
    public function getText($index = 0)
    {
        if (null === $this->texts) {
            $this->texts = $this->readTexts();
        }

        if (!isset($this->texts[$index])) {
            return null;
        }

        return $this->texts[$index];
    }
}
