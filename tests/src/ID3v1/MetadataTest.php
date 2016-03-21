<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

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
     * Create resource from empty file
     *
     * @return resource
     */
    public function createResourceFromEmptyFile()
    {
        $target = tempnam(sys_get_temp_dir(), 'php');

        return fopen($target, 'r+b');
    }

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
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/notags.mp3');
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

    /**
     * Test reading non-existing metadata
     */
    public function testReadingNonExistentMetadata()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/notags.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);

        $this->assertNull($metadata->read());
    }

    /**
     * Test reading existing ID3 v1.0 metadata tag
     *
     * @uses GravityMedia\Metadata\ID3v1\TagFactory
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testReadingExistentID3v10MetadataTag()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);
        $tag = $metadata->read();

        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_10, $tag->getVersion());
        $this->assertSame('Title', $tag->getTitle());
        $this->assertSame('Artist', $tag->getArtist());
        $this->assertSame('Album', $tag->getAlbum());
        $this->assertSame(2003, $tag->getYear());
        $this->assertSame('Comment', $tag->getComment());
        $this->assertSame('Hip-Hop', $tag->getGenre());
    }

    /**
     * Test reading existing ID3 v1.1 metadata tag
     *
     * @uses GravityMedia\Metadata\ID3v1\TagFactory
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testReadingExistentID3v11MetadataTag()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/id3v11.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);
        $tag = $metadata->read();

        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_11, $tag->getVersion());
        $this->assertSame('Title', $tag->getTitle());
        $this->assertSame('Artist', $tag->getArtist());
        $this->assertSame('Album', $tag->getAlbum());
        $this->assertSame(2003, $tag->getYear());
        $this->assertSame('Comment', $tag->getComment());
        $this->assertSame(12, $tag->getTrack());
        $this->assertSame('Hip-Hop', $tag->getGenre());
    }

    /**
     * Test that writing an invalid metadata tag throws an exception
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid tag argument
     */
    public function testWritingInvalidMetadataTagThrowsException()
    {
        $resource = $this->createResourceFromEmptyFile();
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);

        $metadata->write($this->getMock('GravityMedia\Metadata\Metadata\TagInterface'));
    }

    /**
     * Test overwriting existing metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\TagFactory
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testOverwritingExistentMetadata()
    {
        $resource = $this->createResourceFromFileCopy(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);
        $tag = $metadata->read();

        $this->assertNotNull($tag);
        $this->assertInstanceOf('GravityMedia\Metadata\ID3v1\Metadata', $metadata->write($tag));
    }

    /**
     * Test writing ID3 v1.0 metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testWritingID3v10Metadata()
    {
        $resource = $this->createResourceFromEmptyFile();
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);

        $tag = new Tag(Version::VERSION_10);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear(2003);
        $tag->setComment('Comment');
        $tag->setGenre('Hip-Hop');

        $this->assertInstanceOf('GravityMedia\Metadata\ID3v1\Metadata', $metadata->write($tag));
    }

    /**
     * Test writing ID3 v1.1 metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testWritingID3v11Metadata()
    {
        $resource = $this->createResourceFromEmptyFile();
        $stream = Stream::fromResource($resource);
        $metadata = new Metadata($stream);

        $tag = new Tag(Version::VERSION_11);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear(2003);
        $tag->setComment('Comment');
        $tag->setTrack(12);
        $tag->setGenre('Hip-Hop');

        $this->assertInstanceOf('GravityMedia\Metadata\ID3v1\Metadata', $metadata->write($tag));
    }
}
