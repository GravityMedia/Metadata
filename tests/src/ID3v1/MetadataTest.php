<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Genre;
use GravityMedia\Metadata\ID3v1\Metadata;
use GravityMedia\Metadata\ID3v1\Tag;
use GravityMedia\Metadata\ID3v1\Version;
use GravityMedia\MetadataTest\Helper\ResourceHelper;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 metadata test class.
 *
 * @package GravityMedia\MetadataTest\ID3v1
 *
 * @covers  GravityMedia\Metadata\ID3v1\Metadata
 * @uses    GravityMedia\Metadata\ID3v1\Filter
 * @uses    GravityMedia\Metadata\ID3v1\Tag
 * @uses    GravityMedia\Metadata\ID3v1\Version
 * @uses    GravityMedia\Metadata\ID3v1\Genre
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that non-existent metadata is detected
     */
    public function testDetectingNonExistentMetadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/no-tags.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test that existent metadata is detected
     */
    public function testDetectingExistentMetadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $this->assertTrue($metadata->exists());
    }

    /**
     * Test stripping non-existent metadata
     */
    public function testStrippingNonExistentMetadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/no-tags.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);
        $metadata->strip();

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test stripping existent metadata
     */
    public function testStrippingExistentMetadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);
        $metadata->strip();

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test reading non-existing metadata
     */
    public function testReadingNonExistentMetadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/no-tags.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $this->assertNull($metadata->read());
    }

    /**
     * Test reading existing ID3 v1.0 metadata tag
     */
    public function testReadingExistentID3v10MetadataTag()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $tag = $metadata->read();
        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_10, $tag->getVersion());
        $this->assertSame('Title', $tag->getTitle());
        $this->assertSame('Artist', $tag->getArtist());
        $this->assertSame('Album', $tag->getAlbum());
        $this->assertSame('2003', $tag->getYear());
        $this->assertSame('Comment', $tag->getComment());
        $this->assertSame(Genre::GENRE_HIP_HOP, $tag->getGenre());
    }

    /**
     * Test reading existing ID3 v1.1 metadata tag
     */
    public function testReadingExistentID3v11MetadataTag()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v11.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $tag = $metadata->read();
        $this->assertNotNull($tag);
        $this->assertSame(Version::VERSION_11, $tag->getVersion());
        $this->assertSame('Title', $tag->getTitle());
        $this->assertSame('Artist', $tag->getArtist());
        $this->assertSame('Album', $tag->getAlbum());
        $this->assertSame('2003', $tag->getYear());
        $this->assertSame('Comment', $tag->getComment());
        $this->assertSame(12, $tag->getTrack());
        $this->assertSame(Genre::GENRE_HIP_HOP, $tag->getGenre());
    }

    /**
     * Test writing ID3 v1.0 metadata
     */
    public function testWritingID3v10Metadata()
    {
        $resourceHelper = new ResourceHelper();
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $tag = new Tag(Version::VERSION_10);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear('2003');
        $tag->setComment('Comment');
        $tag->setGenre(Genre::GENRE_HIP_HOP);

        $this->assertInstanceOf(Metadata::class, $metadata->write($tag));
    }

    /**
     * Test overwriting ID3 v1.0 metadata
     */
    public function testOverwritingID3v10Metadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v10.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $tag = new Tag(Version::VERSION_10);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear('2003');
        $tag->setComment('Comment');
        $tag->setGenre(Genre::GENRE_HIP_HOP);

        $this->assertInstanceOf(Metadata::class, $metadata->write($tag));
    }

    /**
     * Test writing ID3 v1.1 metadata
     */
    public function testWritingID3v11Metadata()
    {
        $resourceHelper = new ResourceHelper();
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $tag = new Tag(Version::VERSION_11);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear('2003');
        $tag->setComment('Comment');
        $tag->setTrack(12);
        $tag->setGenre(Genre::GENRE_HIP_HOP);

        $this->assertInstanceOf(Metadata::class, $metadata->write($tag));
    }

    /**
     * Test overwriting ID3 v1.1 metadata
     */
    public function testOverwritingID3v11Metadata()
    {
        $resourceHelper = new ResourceHelper(__DIR__ . '/../../resources/id3v11.mp3');
        $stream = Stream::fromResource($resourceHelper->getResource());
        $metadata = new Metadata($stream);

        $tag = new Tag(Version::VERSION_11);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear('2003');
        $tag->setComment('Comment');
        $tag->setTrack(12);
        $tag->setGenre(Genre::GENRE_HIP_HOP);

        $this->assertInstanceOf(Metadata::class, $metadata->write($tag));
    }
}
