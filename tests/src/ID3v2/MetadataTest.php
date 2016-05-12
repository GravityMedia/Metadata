<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v2;

use GravityMedia\Metadata\ID3v2\Metadata;
use GravityMedia\Metadata\ID3v2\Restriction;
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
        $this->assertSame(0, $tag->getRevision());
        $this->assertSame(0, $tag->getPadding());
        $this->assertSame(0, $tag->getCrc32());

        $frames = $tag->getFrames();
        $this->assertCount(7, $frames);

        $frame = $frames[0];
        $this->assertEquals('COMM', $frame->getName());
        $this->assertEquals('ID3v1.x Comment', $frame->getContent());

        $frame = $frames[1];
        $this->assertEquals('TORY', $frame->getName());
        $this->assertEquals('2003', $frame->getContent());

        $frame = $frames[2];
        $this->assertEquals('TIT2', $frame->getName());
        $this->assertEquals('Title', $frame->getContent());

        $frame = $frames[3];
        $this->assertEquals('TPE1', $frame->getName());
        $this->assertEquals('Artist', $frame->getContent());

        $frame = $frames[4];
        $this->assertEquals('TALB', $frame->getName());
        $this->assertEquals('Album', $frame->getContent());

        $frame = $frames[5];
        $this->assertEquals('TRCK', $frame->getName());
        $this->assertEquals('12', $frame->getContent());

        $frame = $frames[6];
        $this->assertEquals('TCON', $frame->getName());
        $this->assertEquals('(7)Hip-Hop', $frame->getContent());

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
        $this->assertSame(0, $tag->getRevision());
        $this->assertSame(0, $tag->getCrc32());
        $this->assertSame(-1, $tag->getRestriction(Restriction::RESTRICTION_TAG_SIZE));
        $this->assertSame(-1, $tag->getRestriction(Restriction::RESTRICTION_TEXT_ENCODING));
        $this->assertSame(-1, $tag->getRestriction(Restriction::RESTRICTION_TEXT_FIELDS_SIZE));
        $this->assertSame(-1, $tag->getRestriction(Restriction::RESTRICTION_IMAGE_ENCODING));
        $this->assertSame(-1, $tag->getRestriction(Restriction::RESTRICTION_IMAGE_SIZE));

        $frames = $tag->getFrames();
        $this->assertCount(7, $frames);

        $frame = $frames[0];
        $this->assertEquals('COMM', $frame->getName());
        $this->assertEquals('ID3v1.x Comment', $frame->getContent());

        $frame = $frames[1];
        $this->assertEquals('TIT2', $frame->getName());
        $this->assertEquals('Title', $frame->getContent());

        $frame = $frames[2];
        $this->assertEquals('TPE1', $frame->getName());
        $this->assertEquals('Artist', $frame->getContent());

        $frame = $frames[3];
        $this->assertEquals('TALB', $frame->getName());
        $this->assertEquals('Album', $frame->getContent());

        $frame = $frames[4];
        $this->assertEquals('TRCK', $frame->getName());
        $this->assertEquals('12', $frame->getContent());

        $frame = $frames[5];
        $this->assertEquals('TCON', $frame->getName());
        $this->assertEquals('(7)Hip-Hop', $frame->getContent());

        $frame = $frames[6];
        $this->assertEquals('TDOR', $frame->getName());
        $this->assertEquals('2003', $frame->getContent());

        //var_dump($tag->getFrames());
    }
}
