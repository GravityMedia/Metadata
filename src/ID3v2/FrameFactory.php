<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\ID3v2\Filter\CharsetFilter;
use GravityMedia\Metadata\ID3v2\Frame\CommentFrame;
use GravityMedia\Metadata\ID3v2\Frame\PictureFrame;
use GravityMedia\Metadata\ID3v2\Frame\TextFrame;
use GravityMedia\Metadata\ID3v2\Reader\LanguageTextFrameReader;
use GravityMedia\Metadata\ID3v2\Reader\PictureFrameReader;
use GravityMedia\Metadata\ID3v2\Reader\TextFrameReader;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 frame factory class.
 *
 * @package GravityMedia\Metadata
 */
class FrameFactory
{
    /**
     * @var CharsetFilter
     */
    private $charsetFilter;

    /**
     * Create ID3v2 frame factory object.
     */
    public function __construct()
    {
        $this->charsetFilter = new CharsetFilter();
    }

    /**
     * Create frame.
     *
     * @param Stream $stream
     * @param string $name
     *
     * @return Frame
     */
    public function createFrame(Stream $stream, $name)
    {
        if ('UFID' === $name) {
            $frame = new Frame();
            $frame->setName($name);

            // TODO: Read unique file identifier.

            return $frame;
        }

        if ('T' === substr($name, 0, 1)) {
            $frame = $this->createTextFrame($stream, $name);

            if ('TXXX' === $name) {
                // TODO: Read user defined text frame.
            }

            return $frame;
        }

        if ('W' === substr($name, 0, 1)) {
            $frame = new Frame();
            $frame->setName($name);

            // TODO: Read URL link frame.

            if ('WXXX' === $name) {
                // TODO: Read user defined URL link frame.
            }

            return $frame;
        }

        if ('APIC' === $name) {
            return $this->createPictureFrame($stream, $name);
        }

        if ('COMM' === $name) {
            return $this->createCommentFrame($stream, $name);
        }

        $frame = new Frame();
        $frame->setName($name);

        return $frame;
    }

    /**
     * Create text frame.
     *
     * @param Stream $stream
     * @param string $name
     *
     * @return TextFrame
     */
    public function createTextFrame(Stream $stream, $name)
    {
        $reader = new TextFrameReader($stream);

        $text = $this->charsetFilter->decode($reader->getText(), $reader->getEncoding());

        $frame = new TextFrame();
        $frame->setName($name);

        return $frame;
    }

    /**
     * Create picture frame.
     *
     * @param Stream $stream
     * @param string $name
     *
     * @return PictureFrame
     */
    public function createPictureFrame(Stream $stream, $name)
    {
        $reader = new PictureFrameReader($stream);

        $frame = new PictureFrame();
        $frame->setName($name);
        $frame->setMimeType($reader->getMimeType());
        $frame->setType($reader->getType());
        $frame->setDescription($this->charsetFilter->decode($reader->getDescription(), $reader->getEncoding()));
        $frame->setData($reader->getData());

        return $frame;
    }

    /**
     * Create comment frame
     *
     * @param Stream $stream
     * @param string $name
     *
     * @return CommentFrame
     */
    public function createCommentFrame(Stream $stream, $name)
    {
        $reader = new LanguageTextFrameReader($stream);

        $description = array_shift($texts);

        $frame = new CommentFrame();
        $frame->setName($name);
        $frame->setLanguage($reader->getLanguage());
        $frame->setDescription($description);
        $frame->setTexts($texts);

        return $frame;
    }
}
