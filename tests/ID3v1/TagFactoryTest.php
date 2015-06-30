<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Metadata;
use GravityMedia\Metadata\ID3v1\Tag;
use GravityMedia\Metadata\ID3v1\TagFactory;

/**
 * ID3v1 tag factory test
 *
 * @package GravityMedia\MetadataTest
 * @covers  GravityMedia\Metadata\ID3v1\TagFactory
 */
class TagFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that creating tag from empty data throws an exception
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid data argument
     */
    public function testCreatingTagThrowsExceptionOnPassingEmptyData()
    {
        $tagFactory = new TagFactory();

        $tagFactory->createTag(null);
    }

    /**
     * Test that creating tag from invalid data throws an exception
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid data argument
     */
    public function testCreatingTagThrowsExceptionOnPassingInvalidData()
    {
        $tagFactory = new TagFactory();

        $tagFactory->createTag(str_repeat('#', 128));
    }

    /**
     * Test creating tag from valid ID3 v1.0 data
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testCreatingTagFromValidID3v10Data()
    {
        $tagFactory = new TagFactory();
        $data = substr(file_get_contents(__DIR__ . '/../../resources/id3v10.mp3'), -128);

        /** @var \GravityMedia\Metadata\ID3v1\Tag $tag */
        $tag = $tagFactory->createTag($data);

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
     * Test creating tag from valid ID3 v1.1 data
     *
     * @uses GravityMedia\Metadata\ID3v1\Tag
     * @uses GravityMedia\Metadata\ID3v1\Genres
     */
    public function testCreatingTagFromValidID3v11Data()
    {
        $tagFactory = new TagFactory();
        $data = substr(file_get_contents(__DIR__ . '/../../resources/id3v11.mp3'), -128);

        /** @var \GravityMedia\Metadata\ID3v1\Tag $tag */
        $tag = $tagFactory->createTag($data);

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
}
