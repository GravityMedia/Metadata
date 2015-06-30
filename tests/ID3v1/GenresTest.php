<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Genres;

/**
 * ID3v1 genres test
 *
 * @package GravityMedia\MetadataTest
 * @covers  GravityMedia\Metadata\ID3v1\Genres
 */
class GenresTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getting name of a known genre index
     */
    public function testGettingNameByKnownIndex()
    {
        $genres = new Genres();

        $this->assertEquals('Hip-Hop', $genres->getNameByIndex(7));
    }

    /**
     * Test getting name of an unknown genre index
     */
    public function testGettingNameByUnknownIndex()
    {
        $genres = new Genres();

        $this->assertNull($genres->getNameByIndex(255));
    }

    /**
     * Test getting index of a known genre name
     */
    public function testGettingIndexByKnownName()
    {
        $genres = new Genres();

        $this->assertEquals(7, $genres->getIndexByName('Hip-Hop'));
    }

    /**
     * Test getting index of an unknown genre name
     */
    public function testGettingIndexByUnknownName()
    {
        $genres = new Genres();

        $this->assertEquals(255, $genres->getIndexByName('Foo-Bar'));
    }
}
