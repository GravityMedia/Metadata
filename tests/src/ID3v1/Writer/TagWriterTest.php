<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1\Writer;

use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;
use GravityMedia\Metadata\ID3v1\Metadata;
use GravityMedia\Metadata\ID3v1\Tag;
use GravityMedia\Metadata\ID3v1\Writer\TagWriter;
use GravityMedia\Stream\Stream;

/**
 * ID3v1 tag writer test
 *
 * @package GravityMedia\MetadataTest\ID3v1\Writer
 *
 * @covers  GravityMedia\Metadata\ID3v1\Writer\TagWriter
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
     * Test writing ID3 v1.0 metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Enum\Version
     * @uses GravityMedia\Metadata\ID3v1\Enum\Genre
     */
    public function testWritingID3v10Metadata()
    {
        $metadata = $this->getMockBuilder(Metadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists'])
            ->getMock();

        $metadata
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));

        $resource = $this->createResourceFromEmptyFile();
        $stream = Stream::fromResource($resource);
        $tagWriter = new TagWriter($metadata, $stream);

        $tag = new Tag(Version::VERSION_10);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear(2003);
        $tag->setComment('Comment');
        $tag->setGenre(Genre::GENRE_HIP_HOP);

        $this->assertInstanceOf(TagWriter::class, $tagWriter->write($tag));
    }

    /**
     * Test writing ID3 v1.1 metadata
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Enum\Version
     * @uses GravityMedia\Metadata\ID3v1\Enum\Genre
     */
    public function testWritingID3v11Metadata()
    {
        $metadata = $this->getMockBuilder(Metadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists'])
            ->getMock();

        $metadata
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));

        $resource = $this->createResourceFromEmptyFile();
        $stream = Stream::fromResource($resource);
        $tagWriter = new TagWriter($metadata, $stream);

        $tag = new Tag(Version::VERSION_11);
        $tag->setTitle('Title');
        $tag->setArtist('Artist');
        $tag->setAlbum('Album');
        $tag->setYear(2003);
        $tag->setComment('Comment');
        $tag->setTrack(12);
        $tag->setGenre(Genre::GENRE_HIP_HOP);

        $this->assertInstanceOf(TagWriter::class, $tagWriter->write($tag));
    }
}
