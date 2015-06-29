<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Tag;

use GravityMedia\Metadata\Exception;

/**
 * ID3v2 tag header
 *
 * @package GravityMedia\Metadata
 */
class Header
{
    /**
     * Tag version 2.2
     */
    const VERSION_22 = 0;

    /**
     * Tag version 2.3
     */
    const VERSION_23 = 1;

    /**
     * Tag version 2.4
     */
    const VERSION_24 = 2;

    /**
     * Unsynchronisation flag
     */
    const FLAG_UNSYNCHRONISATION = 0;

    /**
     * Compression flag
     */
    const FLAG_COMPRESSION = 1;

    /**
     * Extended header flag
     */
    const FLAG_EXTENDED_HEADER = 2;

    /**
     * Experimental indicator flag
     */
    const FLAG_EXPERIMENTAL_INDICATOR = 3;

    /**
     * Footer present flag
     */
    const FLAG_FOOTER_PRESENT = 4;

    /**
     * CRC data present flag
     */
    const FLAG_CRC_DATA_PRESENT = 5;

    /**
     * Tag is an update flag
     */
    const FLAG_TAG_IS_AN_UPDATE = 6;

    /**
     * Tag restrictions flag
     */
    const FLAG_TAG_RESTRICTIONS = 7;

    /**
     * Valid versions
     *
     * @var array
     */
    protected static $validVersions = array(self::VERSION_22, self::VERSION_23, self::VERSION_24);

    /**
     * Valid flags
     *
     * @var array
     */
    protected static $validFlags = array(
        self::VERSION_22 => array(self::FLAG_UNSYNCHRONISATION, self::FLAG_COMPRESSION),
        self::VERSION_23 => array(self::FLAG_UNSYNCHRONISATION, self::FLAG_EXTENDED_HEADER,
            self::FLAG_EXPERIMENTAL_INDICATOR, self::FLAG_CRC_DATA_PRESENT),
        self::VERSION_24 => array(self::FLAG_UNSYNCHRONISATION, self::FLAG_EXTENDED_HEADER,
            self::FLAG_EXPERIMENTAL_INDICATOR, self::FLAG_FOOTER_PRESENT, self::FLAG_TAG_IS_AN_UPDATE,
            self::FLAG_CRC_DATA_PRESENT, self::FLAG_TAG_RESTRICTIONS)
    );

    /**
     * @var int
     */
    protected $version;

    /**
     * @var int
     */
    protected $revision;

    /**
     * @var array
     */
    protected $flags;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $extendedSize;

    /**
     * Create ID3v2 tag header object
     *
     * @param int $version The version (default is 2: v2.4)
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = self::VERSION_24)
    {
        if (!in_array($version, self::$validVersions)) {
            throw new Exception\InvalidArgumentException('Invalid version argument');
        }

        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set revision
     *
     * @param int $revision
     *
     * @return $this
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
        return $this;
    }

    /**
     * Get revision
     *
     * @return int
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set flags
     *
     * @param array $flags
     *
     * @throws Exception\InvalidArgumentException An exception is thrown if flags argument contains invalid flag
     *
     * @return $this
     */
    public function setFlags(array $flags)
    {
        foreach ($flags as $flag => $value) {
            $this->setFlag($flag, $value);
        }

        return $this;
    }

    /**
     * Set flag
     *
     * @param int  $flag
     * @param bool $value
     *
     * @throws Exception\InvalidArgumentException An exception is thrown if flags argument contains invalid flag
     *
     * @return $this
     */
    public function setFlag($flag, $value = true)
    {
        $version = $this->getVersion();
        if (!in_array($flag, self::$validFlags[$version])) {
            throw new Exception\InvalidArgumentException('Invalid flag argument');
        }

        $this->flags[$flag] = $value;
        return $this;
    }

    /**
     * Get flag
     *
     * @param int  $flag
     * @param bool $default
     *
     * @return bool
     */
    public function getFlag($flag, $default = false)
    {
        if (!isset($this->flags[$flag])) {
            return $default;
        }

        return $this->flags[$flag];
    }

    /**
     * Set size
     *
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set extended size
     *
     * @param int $extendedSize
     *
     * @return $this
     */
    public function setExtendedSize($extendedSize)
    {
        $this->extendedSize = $extendedSize;
        return $this;
    }

    /**
     * Get extended size
     *
     * @return int
     */
    public function getExtendedSize()
    {
        return $this->extendedSize;
    }
}
