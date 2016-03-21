<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

use GravityMedia\Metadata\Exception;

/**
 * ID3v2 header
 *
 * @package GravityMedia\Metadata
 */
class Header
{
    /**
     * Tag version 2.2
     */
    const VERSION_22 = 2;

    /**
     * Tag version 2.3
     */
    const VERSION_23 = 3;

    /**
     * Tag version 2.4
     */
    const VERSION_24 = 4;

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
     * @var bool
     */
    protected $unsynchronisation;

    /**
     * @var bool
     */
    protected $compression;

    /**
     * @var bool
     */
    protected $extendedHeader;

    /**
     * @var bool
     */
    protected $experimentalIndicator;

    /**
     * @var bool
     */
    protected $footerPresent;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $tagSize;

    /**
     * @var int
     */
    protected $completeTagSize;

    /**
     * Create ID3v2 header object
     *
     * @param int $version The version (default is 4: v2.4)
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
     * Set unsynchronisation flag
     *
     * @param boolean $unsynchronisation
     *
     * @return $this
     */
    public function setUnsynchronisation($unsynchronisation)
    {
        $this->unsynchronisation = $unsynchronisation;
        return $this;
    }

    /**
     * Is unsynchronisation flag set
     *
     * @return boolean
     */
    public function isUnsynchronisation()
    {
        return $this->unsynchronisation;
    }

    /**
     * Set compression flas
     *
     * @param boolean $compression
     *
     * @return $this
     */
    public function setCompression($compression)
    {
        $this->compression = $compression;
        return $this;
    }

    /**
     * Is compression flag set
     *
     * @return boolean
     */
    public function isCompression()
    {
        return $this->compression;
    }

    /**
     * Set extended header flag
     *
     * @param boolean $extendedHeader
     *
     * @return $this
     */
    public function setExtendedHeader($extendedHeader)
    {
        $this->extendedHeader = $extendedHeader;
        return $this;
    }

    /**
     * Is extended header flag set
     *
     * @return boolean
     */
    public function isExtendedHeader()
    {
        return $this->extendedHeader;
    }

    /**
     * Set experimental indicator flag
     *
     * @param boolean $experimentalIndicator
     *
     * @return $this
     */
    public function setExperimentalIndicator($experimentalIndicator)
    {
        $this->experimentalIndicator = $experimentalIndicator;
        return $this;
    }

    /**
     * Is experimental indicator flag set
     *
     * @return boolean
     */
    public function isExperimentalIndicator()
    {
        return $this->experimentalIndicator;
    }

    /**
     * Set footer present flag
     *
     * @param boolean $footerPresent
     *
     * @return $this
     */
    public function setFooterPresent($footerPresent)
    {
        $this->footerPresent = $footerPresent;
        return $this;
    }

    /**
     * Is footer present flag set
     *
     * @return boolean
     */
    public function isFooterPresent()
    {
        return $this->footerPresent;
    }

    /**
     * Set size in bytes
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
     * Get size in bytes
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get tag size in bytes
     *
     * @return int
     */
    public function getTagSize()
    {
        return $this->tagSize;
    }

    /**
     * Set tag size in bytes
     *
     * @param int $tagSize
     *
     * @return $this
     */
    public function setTagSize($tagSize)
    {
        $this->tagSize = $tagSize;
        return $this;
    }

    /**
     * Get complete tag size in bytes
     *
     * @return int
     */
    public function getCompleteTagSize()
    {
        return $this->completeTagSize;
    }

    /**
     * Set complete tag size in bytes
     *
     * @param int $completeTagSize
     *
     * @return $this
     */
    public function setCompleteTagSize($completeTagSize)
    {
        $this->completeTagSize = $completeTagSize;
        return $this;
    }
}
