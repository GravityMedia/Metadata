<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Flag\FrameFlag;
use GravityMedia\Metadata\ID3v2\Version;

/**
 * ID3v2 frame header reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class FrameHeaderReader extends AbstractReader
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $dataLength;

    /**
     * Read ID3v2 frame name.
     *
     * @return string
     */
    protected function readName()
    {
        $this->getStream()->seek($this->getOffset());

        if (Version::VERSION_22 === $this->getVersion()) {
            return rtrim($this->getStream()->read(3));
        }

        return rtrim($this->getStream()->read(4));
    }

    /**
     * Get ID3v2 frame name.
     *
     * @return string
     */
    public function getName()
    {
        if (null === $this->name) {
            $this->name = $this->readName();
        }

        return $this->name;
    }

    /**
     * Read ID3v2 frame size.
     *
     * @return int
     */
    protected function readSize()
    {
        if (Version::VERSION_22 === $this->getVersion()) {
            $this->getStream()->seek($this->getOffset() + 3);

            return $this->getStream()->readUInt24();
        }

        $this->getStream()->seek($this->getOffset() + 4);

        $value = $this->getStream()->readUInt32();

        if (Version::VERSION_23 === $this->getVersion()) {
            return $value;
        }

        return $this->getSynchsafeIntegerFilter()->decode($value);
    }

    /**
     * Read ID3v2 frame flags.
     *
     * @return bool[]
     */
    protected function readFlags()
    {
        if (Version::VERSION_22 === $this->getVersion()) {
            return [];
        }

        $this->getStream()->seek($this->getOffset() + 8);

        $flags = $this->getStream()->readUInt16();

        if (Version::VERSION_23 === $this->getVersion()) {
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
    protected function readDataLength()
    {
        if (!$this->isFlagEnabled(FrameFlag::FLAG_DATA_LENGT_INDICATOR)) {
            return $this->getSize();
        }

        $this->getStream()->seek($this->getOffset() + 10);

        return $this->getSynchsafeIntegerFilter()->decode($this->getStream()->readUInt32());
    }

    /**
     * Get ID3v2 frame data length.
     *
     * @return int
     */
    public function getDataLength()
    {
        if (null === $this->dataLength) {
            $this->dataLength = $this->readDataLength();
        }

        return $this->dataLength;
    }
}
