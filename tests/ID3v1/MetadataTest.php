<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Metadata;
use GravityMedia\Metadata\ID3v1\Tag;

/**
 * ID3v1 metadata test
 *
 * @package GravityMedia\MetadataTest
 * @covers  GravityMedia\Metadata\ID3v1\Metadata
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that metadata object returns stream from existent file
     */
    public function testMetadataObjectReturnsStreamFromExistentFile()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../resources/notags.mp3');
        $metadata = new Metadata($file);

        $this->assertInstanceOf('GravityMedia\Stream\Stream', $metadata->getStream());
    }

    /**
     * Test that metadata object returns stream from non-existent file
     */
    public function testMetadataObjectReturnsStreamFromNonExistentFile()
    {
        $file = new \SplFileInfo(sys_get_temp_dir() . '/php' . uniqid());
        $metadata = new Metadata($file);

        $this->assertInstanceOf('GravityMedia\Stream\Stream', $metadata->getStream());

        unlink($file);
    }

    /**
     * Test that non-existent metadata is detected on empty file
     */
    public function testNonExistentMetadataDetectionOnEmptyFile()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        $metadata = new Metadata($file);

        $this->assertFalse($metadata->exists());

        unlink($file);
    }

    /**
     * Test that non-existent metadata is detected
     */
    public function testNonExistentMetadataDetection()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../resources/notags.mp3');
        $metadata = new Metadata($file);

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test that existent metadata is detected
     */
    public function testExistentMetadataDetection()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../resources/id3v10.mp3');
        $metadata = new Metadata($file);

        $this->assertTrue($metadata->exists());
    }

    /**
     * Test stripping non-existent metadata
     */
    public function testStrippingNonExistentMetadata()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        copy(__DIR__ . '/../../resources/notags.mp3', $file);
        $metadata = new Metadata($file);
        $metadata->strip();

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test stripping existent metadata
     */
    public function testStrippingExistentMetadata()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        copy(__DIR__ . '/../../resources/id3v10.mp3', $file);
        $metadata = new Metadata($file);
        $metadata->strip();

        $this->assertFalse($metadata->exists());
    }

    /**
     * Test reading non-existing metadata
     */
    public function testReadingNonExistentMetadata()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../resources/notags.mp3');
        $metadata = new Metadata($file);

        $this->assertNull($metadata->read());
    }

    /**
     * Test reading existing ID3 v1.0 metadata tag
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     */
    public function testReadingExistentID3v10MetadataTag()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../resources/id3v10.mp3');
        $metadata = new Metadata($file);

        $tag = $metadata->read();
        $this->assertNotNull($tag);
        $this->assertEquals(Tag::VERSION_10, $tag->getVersion());
        $this->assertEquals('Title', $tag->getTitle());
        $this->assertEquals('Artist', $tag->getArtist());
        $this->assertEquals('Album', $tag->getAlbum());
        $this->assertEquals(2003, $tag->getYear());
        $this->assertEquals('Comment', $tag->getComment());
        $this->assertEquals('Hip-Hop', $tag->getGenre());
    }

    /**
     * Test reading existing ID3 v1.1 metadata tag
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     */
    public function testReadingExistentID3v11MetadataTag()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../resources/id3v11.mp3');
        $metadata = new Metadata($file);

        $tag = $metadata->read();
        $this->assertNotNull($tag);
        $this->assertEquals(Tag::VERSION_11, $tag->getVersion());
        $this->assertEquals('Title', $tag->getTitle());
        $this->assertEquals('Artist', $tag->getArtist());
        $this->assertEquals('Album', $tag->getAlbum());
        $this->assertEquals(2003, $tag->getYear());
        $this->assertEquals('Comment', $tag->getComment());
        $this->assertEquals(12, $tag->getTrack());
        $this->assertEquals('Hip-Hop', $tag->getGenre());
    }

    /**
     * Test that writing an invalid metadata tag throws an exception
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid tag argument
     */
    public function testWritingInvalidMetadataTagThrowsException()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        $metadata = new Metadata($file);

        $metadata->write($this->getMock('GravityMedia\Metadata\TagInterface'));
    }

    /**
     * Test overwriting existing metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     */
    public function testOverwritingExistentMetadata()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        copy(__DIR__ . '/../../resources/id3v10.mp3', $file);
        $metadata = new Metadata($file);
        $tag = $metadata->read();

        $this->assertNotNull($tag);
        $this->assertInstanceOf('GravityMedia\Metadata\ID3v1\Metadata', $metadata->write($tag));
    }

    /**
     * Test writing ID3 v1.0 metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     */
    public function testWritingID3v10Metadata()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        $metadata = new Metadata($file);

        $tag = new Tag(Tag::VERSION_10);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear(2003);
        $tag->setComment('Comment');
        $tag->setGenre('Hip-Hop');

        $this->assertInstanceOf('GravityMedia\Metadata\ID3v1\Metadata', $metadata->write($tag));

        unlink($file);
    }

    /**
     * Test writing ID3 v1.1 metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     */
    public function testWritingID3v11Metadata()
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php'));
        $metadata = new Metadata($file);

        $tag = new Tag(Tag::VERSION_11);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear(2003);
        $tag->setComment('Comment');
        $tag->setTrack(12);
        $tag->setGenre('Hip-Hop');

        $this->assertInstanceOf('GravityMedia\Metadata\ID3v1\Metadata', $metadata->write($tag));

        unlink($file);
    }
}
