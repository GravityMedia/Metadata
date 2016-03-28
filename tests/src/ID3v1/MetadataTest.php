<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\ID3v1\Metadata;
use GravityMedia\Metadata\ID3v1\Tag;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 metadata test
 *
 * @package GravityMedia\MetadataTest\ID3v1
 *
 * @covers  GravityMedia\Metadata\ID3v1\Metadata
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create resource from file copy
     *
     * @param string $source
     *
     * @return resource
     */
    public function createResourceFromFileCopy($source)
    {
        $target = tempnam(sys_get_temp_dir(), 'php');
        copy($source, $target);

        return fopen($target, 'r+b');
    }

    /**
     * Test that non-existent metadata is detected
     */
    public function testNonExistentMetadataDetection()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/no-tags.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test that existent metadata is detected
     */
    public function testExistentMetadataDetection()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);

        $this->assertTrue($metadata->exists());
    }

    /**
     * Test stripping existent metadata
     */
    public function testStrippingExistentMetadata()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);
        $metadata->strip();

        $this->assertFalse($metadata->exists());
    }
}
