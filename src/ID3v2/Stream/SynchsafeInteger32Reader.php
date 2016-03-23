<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2\Stream;

use GravityMedia\Stream\Reader\Integer32Reader;

/**
 * Synchsafe 32-bit integer (long) reader
 *
 * @package GravityMedia\Metadata\ID3v2\StreamReader
 */
class SynchsafeInteger32Reader extends Integer32Reader
{
    /**
     * {@inheritdoc}
     */
    public function read()
    {
        $value = parent::read();

        return ($value & 0x7f) | ($value & 0x7f00) >> 1 | ($value & 0x7f0000) >> 2 | ($value & 0x7f000000) >> 3;
    }
}
