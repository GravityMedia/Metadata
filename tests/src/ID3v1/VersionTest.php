<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Version;

/**
 * ID3v1 version test class.
 *
 * @package GravityMedia\MetadataTest\ID3v1
 *
 * @covers  GravityMedia\Metadata\ID3v1\Version
 */
class VersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that all version values are returned
     */
    public function testVersionValues()
    {
        $values = [
            Version::VERSION_10,
            Version::VERSION_11
        ];

        $this->assertSame($values, Version::values());
    }
}
