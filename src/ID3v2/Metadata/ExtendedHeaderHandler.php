<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Metadata;

use GravityMedia\Metadata\ID3v2\Flag\ExtendedHeaderFlag;
use GravityMedia\Metadata\ID3v2\Restriction;
use GravityMedia\Metadata\ID3v2\Version;

/**
 * ID3v2 extended header handler class.
 *
 * @package GravityMedia\Metadata\ID3v2\Metadata
 */
class ExtendedHeaderHandler extends AbstractHandler
{
    /**
     * @var int
     */
    private $padding;

    /**
     * @var int
     */
    private $crc32;

    /**
     * @var int[]
     */
    private $restrictions;

    /**
     * Read ID3v2 extended header size.
     *
     * @return int
     */
    protected function readSize()
    {
        $this->getStream()->seek($this->getOffset());

        $value = $this->getStream()->readUInt32();

        if (Version::VERSION_23 === $this->getVersion()) {
            return $value;
        }

        return $this->getSynchsafeIntegerFilter()->decode($value);
    }

    /**
     * Read ID3v2 extended header flags.
     *
     * @return bool[]
     */
    public function readFlags()
    {
        if (Version::VERSION_23 === $this->getVersion()) {
            $this->getStream()->seek($this->getOffset() + 4);

            $flags = $this->getStream()->readUInt16();

            return [
                ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x8000)
            ];
        }

        $this->getStream()->seek($this->getOffset() + 5);

        $flags = $this->getStream()->readUInt8();

        return [
            ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE => (bool)($flags & 0x40),
            ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT => (bool)($flags & 0x20),
            ExtendedHeaderFlag::FLAG_TAG_RESTRICTIONS => (bool)($flags & 0x10)
        ];
    }

    /**
     * Read ID3v2 extended header padding.
     *
     * @return int
     */
    protected function readPadding()
    {
        if (Version::VERSION_24 === $this->getVersion()) {
            return 0;
        }

        $this->getStream()->seek($this->getOffset() + 6);

        return $this->getStream()->readUInt32();
    }

    /**
     * Get ID3v2 extended header padding.
     *
     * @return int
     */
    public function getPadding()
    {
        if (null === $this->padding) {
            $this->padding = $this->readPadding();
        }

        return $this->padding;
    }

    /**
     * Read ID3v2 extended header CRC-32 data.
     *
     * @return int
     */
    protected function readCrc32()
    {
        if (!$this->isFlagEnabled(ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT)) {
            return 0;
        }

        if (Version::VERSION_23 === $this->getVersion()) {
            $this->getStream()->seek($this->getOffset() + 10);

            return $this->getStream()->readUInt32();
        }

        $offset = 7;
        if ($this->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE)) {
            $offset += 1;
        }

        $this->getStream()->seek($this->getOffset() + $offset);

        return $this->getStream()->readUInt8() * 0x10000000 +
        $this->getSynchsafeIntegerFilter()->decode($this->getStream()->readUInt32());
    }

    /**
     * Get ID3v2 extended header CRC-32 data.
     *
     * @return int
     */
    public function getCrc32()
    {
        if (null === $this->crc32) {
            $this->crc32 = $this->readCrc32();
        }

        return $this->crc32;
    }

    /**
     * Read ID3v2 extended header restrictions.
     *
     * @return int[]
     */
    protected function readRestrictions()
    {
        if (!$this->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_RESTRICTIONS)) {
            return [];
        }

        $offset = 6;
        if ($this->isFlagEnabled(ExtendedHeaderFlag::FLAG_TAG_IS_AN_UPDATE)) {
            $offset += 1;
        }
        if ($this->isFlagEnabled(ExtendedHeaderFlag::FLAG_CRC_DATA_PRESENT)) {
            $offset += 6;
        }

        $this->getStream()->seek($this->getOffset() + $offset);

        $restrictions = $this->getStream()->readUInt8();

        return [
            Restriction::RESTRICTION_TAG_SIZE => ($restrictions & 0xc0) >> 6,
            Restriction::RESTRICTION_TEXT_ENCODING => ($restrictions & 0x20) >> 5,
            Restriction::RESTRICTION_TEXT_FIELDS_SIZE => ($restrictions & 0x18) >> 3,
            Restriction::RESTRICTION_IMAGE_ENCODING => ($restrictions & 0x04) >> 2,
            Restriction::RESTRICTION_IMAGE_SIZE => ($restrictions & 0x03) >> 0
        ];
    }

    /**
     * Get ID3v2 extended header restrictions.
     *
     * @return int[]
     */
    public function getRestrictions()
    {
        if (null === $this->restrictions) {
            $this->restrictions = $this->readRestrictions();
        }

        return $this->restrictions;
    }
}
