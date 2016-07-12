<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Reader;

/**
 * ID3v2 language text frame reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class LanguageTextFrameReader extends Reader
{
    /**
     * @var int
     */
    private $encoding;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $text;

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
     * @return string
     */
    protected function readText()
    {
        $offset = 4;
        $length = $this->getStream()->getSize() - $offset;
        if ($length < 1) {
            return '';
        }

        $this->getStream()->seek($this->getOffset() + $offset);

        return $this->getStream()->read($length);
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        if (null === $this->text) {
            $this->text = $this->readText();
        }

        return $this->text;
    }
}
