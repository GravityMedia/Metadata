<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Flag\HeaderFlag;
use GravityMedia\Metadata\ID3v2\Version;

/**
 * ID3v2 header reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
class HeaderReader extends AbstractReader
{
    /**
     * @var int
     */
    private $revision;

    /**
     * Read ID3v2 revision.
     *
     * @return int
     */
    protected function readRevision()
    {
        $this->getStream()->seek($this->getOffset());

        return $this->getStream()->readUInt8();
    }

    /**
     * Get $revision
     *
     * @return int
     */
    public function getRevision()
    {
        if (null === $this->revision) {
            $this->revision = $this->readRevision();
        }

        return $this->revision;
    }

    /**
     * Read ID3v2 header flags.
     *
     * @return bool[]
     */
    protected function readFlags()
    {
        $this->getStream()->seek($this->getOffset() + 1);

        $flags = $this->getStream()->readUInt8();

        if (Version::VERSION_22 === $this->getVersion()) {
            return [
                HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                HeaderFlag::FLAG_COMPRESSION => (bool)($flags & 0x40)
            ];
        }

        if (Version::VERSION_23 === $this->getVersion()) {
            return [
                HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
                HeaderFlag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
                HeaderFlag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20)
            ];
        }

        return [
            HeaderFlag::FLAG_UNSYNCHRONISATION => (bool)($flags & 0x80),
            HeaderFlag::FLAG_EXTENDED_HEADER => (bool)($flags & 0x40),
            HeaderFlag::FLAG_EXPERIMENTAL_INDICATOR => (bool)($flags & 0x20),
            HeaderFlag::FLAG_FOOTER_PRESENT => (bool)($flags & 0x10)
        ];
    }

    /**
     * Read ID3v2 header size.
     *
     * @return int
     */
    protected function readSize()
    {
        $this->getStream()->seek($this->getOffset() + 2);

        return $this->getSynchsafeIntegerFilter()->decode($this->getStream()->readUInt32());
    }
}
