<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\StreamContainer;

/**
 * ID3v2 picture frame reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class PictureFrameReader extends StreamContainer
{
    /**
     * @var int
     */
    private $encoding;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $data;

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
     * Read mime type.
     *
     * @return string
     */
    protected function readMimeType()
    {
        $this->getStream()->seek($this->getOffset() + 1);

        $mimeType = '';
        while (!$this->getStream()->eof()) {
            $char = $this->getStream()->read(1);
            if ("\x00" === $char) {
                break;
            }

            $mimeType .= $char;
        }

        return $mimeType;
    }

    /**
     * Get mime type.
     *
     * @return string
     */
    public function getMimeType()
    {
        if (null === $this->mimeType) {
            $this->mimeType = $this->readMimeType();
        }

        return $this->mimeType;
    }

    /**
     * Get type.
     *
     * @return int
     */
    protected function readType()
    {
        $offset = strlen($this->getMimeType()) + 2;

        $this->getStream()->seek($this->getOffset() + $offset);

        return $this->getStream()->readUInt8();
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        if (null === $this->type) {
            $this->type = $this->readType();
        }

        return $this->type;
    }

    /**
     * Read description.
     *
     * @return string
     */
    protected function readDescription()
    {
        $offset = strlen($this->getMimeType()) + 3;

        $this->getStream()->seek($this->getOffset() + $offset);

        $description = '';
        while (!$this->getStream()->eof()) {
            $char = $this->getStream()->read(1);
            if ("\x00" === $char) {
                break;
            }

            $description .= $char;
        }

        return $description;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        if (null === $this->description) {
            $this->description = $this->readDescription();
        }

        return $this->description;
    }

    /**
     * Read data.
     *
     * @return string
     */
    protected function readData()
    {
        $offset = strlen($this->getMimeType()) + strlen($this->getDescription()) + 4;
        $length = $this->getStream()->getSize() - $offset;
        if ($length < 1) {
            return '';
        }

        $this->getStream()->seek($this->getOffset() + $offset);

        return $this->getStream()->read($length);
    }

    /**
     * Get data.
     *
     * @return string
     */
    public function getData()
    {
        if (null === $this->data) {
            $this->data = $this->readData();
        }

        return $this->data;
    }
}
