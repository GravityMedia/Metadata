<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v2;

use GravityMedia\Metadata\ID3v2\Metadata;
use GravityMedia\Metadata\ID3v2\Version;
use GravityMedia\MetadataTest\Helper\ResourceHelper;

/**
 * ID3v2 metadata test class.
 *
 * @package GravityMedia\MetadataTest\ID3v2
 *
 * @covers  GravityMedia\Metadata\ID3v2\Metadata
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test reading existing ID3 v2.3 metadata tag
     */
    public function testReadingExistentID3v23MetadataTag()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v23.mp3');
        $metadata = Metadata::fromResource($resourceHelper->getResource());

        $tag = $metadata->read();
        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_23, $tag->getVersion());

        //var_dump($tag->getFrames());
    }

    /**
     * Test reading existing ID3 v2.4 metadata tag
     */
    public function testReadingExistentID3v24MetadataTag()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v24.mp3');
        $metadata = Metadata::fromResource($resourceHelper->getResource());

        $tag = $metadata->read();
        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_24, $tag->getVersion());

        //var_dump($tag->getFrames());
    }
}
