<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1\Reader;

use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\ID3v1\Metadata;
use GravityMedia\Metadata\ID3v1\Reader\TagReader;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 tag reader test
 *
 * @package GravityMedia\MetadataTest\ID3v1\Reader
 *
 * @covers  GravityMedia\Metadata\ID3v1\Reader\TagReader
 */
class TagReaderTest extends \PHPUnit_Framework_TestCase
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
     * Test reading non-existing metadata
     */
    public function testReadingNonExistentMetadata()
    {
        $metadata = $this->getMockBuilder(Metadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists'])
            ->getMock();

        $metadata
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));

        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../../resources/no-tags.mp3');
        $stream = Stream::fromResource($resource);
        $tagReader = new TagReader($metadata, $stream);

        $this->assertNull($tagReader->read());
    }

    /**
     * Test reading existing ID3 v1.0 metadata tag
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Enum\Version
     * @uses GravityMedia\Metadata\ID3v1\Enum\Genre
     */
    public function testReadingExistentID3v10MetadataTag()
    {
        $metadata = $this->getMockBuilder(Metadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists'])
            ->getMock();

        $metadata
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true));

        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resource);
        $tagReader = new TagReader($metadata, $stream);
        $tag = $tagReader->read();

        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_10, $tag->getVersion());
        $this->assertSame('Title', $tag->getTitle());
        $this->assertSame('Artist', $tag->getArtist());
        $this->assertSame('Album', $tag->getAlbum());
        $this->assertSame(2003, $tag->getYear());
        $this->assertSame('Comment', $tag->getComment());
        $this->assertSame(Genre::GENRE_HIP_HOP, $tag->getGenre());
    }

    /**
     * Test reading existing ID3 v1.1 metadata tag
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Enum\Version
     * @uses GravityMedia\Metadata\ID3v1\Enum\Genre
     */
    public function testReadingExistentID3v11MetadataTag()
    {
        $metadata = $this->getMockBuilder(Metadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists'])
            ->getMock();

        $metadata
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true));

        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../../resources/id3v11.mp3');
        $stream = Stream::fromResource($resource);
        $tagReader = new TagReader($metadata, $stream);
        $tag = $tagReader->read();

        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_11, $tag->getVersion());
        $this->assertSame('Title', $tag->getTitle());
        $this->assertSame('Artist', $tag->getArtist());
        $this->assertSame('Album', $tag->getAlbum());
        $this->assertSame(2003, $tag->getYear());
        $this->assertSame('Comment', $tag->getComment());
        $this->assertSame(12, $tag->getTrack());
        $this->assertSame(Genre::GENRE_HIP_HOP, $tag->getGenre());
    }
}
