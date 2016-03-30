<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Encoder\Unsynchronisation;
use GravityMedia\Metadata\ID3v2\Enum\FrameFlag;
use GravityMedia\Metadata\ID3v2\Enum\Version;
use GravityMedia\Metadata\ID3v2\Frame;
use GravityMedia\Metadata\ID3v2\Stream\SynchsafeInteger32Reader;
use GravityMedia\Metadata\ID3v2\FrameInterface;
use GravityMedia\Metadata\ID3v2\HeaderInterface;
use GravityMedia\Stream\Enum\ByteOrder;
use GravityMedia\Stream\Reader\Integer16Reader;
use GravityMedia\Stream\Reader\Integer24Reader;
use GravityMedia\Stream\Reader\Integer32Reader;
use GravityMedia\Stream\StreamInterface;

/**
 * ID3v2 Frame reader
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class FrameReader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var HeaderInterface
     */
    protected $header;

    /**
     * @var Integer16Reader
     */
    protected $integer16Reader;

    /**
     * @var Integer24Reader
     */
    protected $integer24Reader;

    /**
     * @var Integer32Reader
     */
    protected $integer32Reader;

    /**
     * @var SynchsafeInteger32Reader
     */
    protected $synchsafeInteger32Reader;

    /**
     * Create ID3v2 frame reader.
     *
     * @param StreamInterface $stream
     * @param HeaderInterface $header
     */
    public function __construct(StreamInterface $stream, HeaderInterface $header)
    {
        $this->stream = $stream;
        $this->header = $header;
    }

    /**
     * Get 16-bit integer reader
     *
     * @return Integer16Reader
     */
    public function getInteger16Reader()
    {
        if (null === $this->integer16Reader) {
            $this->integer16Reader = new Integer16Reader($this->stream);
            $this->integer16Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->integer16Reader;
    }

    /**
     * Get 24-bit integer reader
     *
     * @return Integer24Reader
     */
    public function getInteger24Reader()
    {
        if (null === $this->integer24Reader) {
            $this->integer24Reader = new Integer24Reader($this->stream);
            $this->integer24Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->integer24Reader;
    }

    /**
     * Get 32-bit integer reader
     *
     * @return Integer32Reader
     */
    public function getInteger32Reader()
    {
        if (null === $this->integer32Reader) {
            $this->integer32Reader = new Integer32Reader($this->stream);
            $this->integer32Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->integer32Reader;
    }

    /**
     * Get synchsafe 32-bit integer reader
     *
     * @return SynchsafeInteger32Reader
     */
    public function getSynchsafeInteger32Reader()
    {
        if (null === $this->synchsafeInteger32Reader) {
            $this->synchsafeInteger32Reader = new SynchsafeInteger32Reader($this->stream);
            $this->synchsafeInteger32Reader->setByteOrder(ByteOrder::BIG_ENDIAN);
        }

        return $this->synchsafeInteger32Reader;
    }

    /**
     * Read ID3v2 frame name.
     *
     * @return string
     */
    protected function readName()
    {
        if (Version::VERSION_22 === $this->header->getVersion()) {
            return rtrim($this->stream->read(3));
        }

        return rtrim($this->stream->read(4));
    }

    /**
     * Read ID3v2 frame size.
     *
     * @return int
     */
    protected function readSize()
    {
        if (Version::VERSION_22 === $this->header->getVersion()) {
            return $this->getInteger24Reader()->read();
        }

        if (Version::VERSION_23 === $this->header->getVersion()) {
            return $this->getInteger32Reader()->read();
        }

        return $this->getSynchsafeInteger32Reader()->read();
    }

    /**
     * Read ID3v2 frame flags.
     *
     * @return array
     */
    protected function readFlags()
    {
        $flags = $this->getInteger16Reader()->read();

        if (Version::VERSION_23 === $this->header->getVersion()) {
            return [
                FrameFlag::FLAG_TAG_ALTER_PRESERVATION => (bool)($flags & 0x8000),
                FrameFlag::FLAG_FILE_ALTER_PRESERVATION => (bool)($flags & 0x4000),
                FrameFlag::FLAG_READ_ONLY => (bool)($flags & 0x2000),
                FrameFlag::FLAG_COMPRESSION => (bool)($flags & 0x0080),
                FrameFlag::FLAG_ENCRYPTION => (bool)($flags & 0x0040),
                FrameFlag::FLAG_GROUPING_IDENTITY => (bool)($flags & 0x0020),
            ];
        }

        return [
            FrameFlag::FLAG_TAG_ALTER_PRESERVATION => (bool)($flags & 0x4000),
            FrameFlag::FLAG_FILE_ALTER_PRESERVATION => (bool)($flags & 0x2000),
            FrameFlag::FLAG_READ_ONLY => (bool)($flags & 0x1000),
            FrameFlag::FLAG_GROUPING_IDENTITY => (bool)($flags & 0x0040),
            FrameFlag::FLAG_COMPRESSION => (bool)($flags & 0x0008),
            FrameFlag::FLAG_ENCRYPTION => (bool)($flags & 0x0004),
            FrameFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x0002),
            FrameFlag::FLAG_DATA_LENGT_INDICATOR => (bool)($flags & 0x0001),
        ];
    }

    /**
     * Read ID3v2 frame data length.
     *
     * @return int
     */
    public function readDataLength()
    {
        return $this->getSynchsafeInteger32Reader()->read();
    }

    /**
     * Read ID3v2 frame.
     *
     * @return FrameInterface
     */
    public function read()
    {
        $name = $this->readName();
        $size = $this->readSize();

        $frame = new Frame();
        $frame
            ->setName($name)
            ->setSize($size);

        // Return empty frame
        if (0 === $size) {
            return $frame;
        }

        // Only in ID3v2.3 and ID3v2.4
        if (Version::VERSION_22 !== $this->header->getVersion()) {
            $frame->setFlags($this->readFlags());
        }

        // Only in ID3v2.4
        if ($frame->isFlagEnabled(FrameFlag::FLAG_DATA_LENGT_INDICATOR)) {
            $frame->setDataLength($this->readDataLength());

            $size -= 4;
        }


        $data = $this->stream->read($size);
        if ($frame->isFlagEnabled(FrameFlag::FLAG_COMPRESSION)) {
            $data = gzuncompress($data);
        }

        // Only in ID3v2.4
        if ($frame->isFlagEnabled(FrameFlag::FLAG_UNSYNCHRONISATION)) {
            $data = Unsynchronisation::decode($data);
        }

        return $frame
            ->setData($data);
    }
}
