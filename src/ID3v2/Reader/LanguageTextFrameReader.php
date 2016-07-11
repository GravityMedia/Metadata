<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Filter\CharsetFilter;
use GravityMedia\Metadata\ID3v2\StreamContainer;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 language text frame reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class LanguageTextFrameReader extends StreamContainer
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
     * @var string
     */
    private $language;

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

    /**
     * Read text.
     *
     * @return string[]
     */
    protected function readText()
    {
        $this->getStream()->seek($this->getOffset() + 4);
        $text = $this->getStream()->read($this->getStream()->getSize() - 4);

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
