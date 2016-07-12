<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Reader;

use GravityMedia\Metadata\ID3v2\Filter\SynchsafeIntegerFilter;
use GravityMedia\Metadata\ID3v2\Reader;
use GravityMedia\Stream\Stream;

/**
 * ID3v2 abstract header reader class.
 *
 * @package GravityMedia\Metadata\ID3v2\Reader
 */
abstract class AbstractHeaderReader extends Reader
{
    /**
     * @var SynchsafeIntegerFilter
     */
    private $synchsafeIntegerFilter;

    /**
     * @var int
     */
    private $size;

    /**
     * @var bool[]
     */
    private $flags;

    /**
     * Create ID3v2 abstract header reader object.
     *
     * @param Stream $stream
     * @param int    $version
     */
    public function __construct(Stream $stream, $version)
    {
        parent::__construct($stream, $version);

        $this->synchsafeIntegerFilter = new SynchsafeIntegerFilter();
    }

    /**
     * Get synchsafe integer filter
     *
     * @return SynchsafeIntegerFilter
     */
    protected function getSynchsafeIntegerFilter()
    {
        return $this->synchsafeIntegerFilter;
    }

    /**
     * Read size.
     *
     * @return int
     */
    abstract protected function readSize();

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        if (null === $this->size) {
            $this->size = $this->readSize();
        }

        return $this->size;
    }

    /**
     * Read flags.
     *
     * @return bool[]
     */
    abstract protected function readFlags();

    /**
     * Whether the flag is enabled.
     *
     * @param int $flag
     *
     * @return bool
     */
    public function isFlagEnabled($flag)
    {
        if (null === $this->flags) {
            $this->flags = $this->readFlags();
        }

        if (isset($this->flags[$flag])) {
            return $this->flags[$flag];
        }

        return false;
    }
}
