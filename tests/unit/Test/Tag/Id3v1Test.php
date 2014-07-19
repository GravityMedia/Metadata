<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Test\Tag;

use GetId3\Module\Tag\Id3v1 as Id3v1Processor;
use GravityMedia\Metadata\SplFileInfo;
use GravityMedia\Metadata\Test\MetadataTestCase;

/**
 * ID3 v1 tag test
 *
 * @package GravityMedia\Metadata\Test\Tag
 */
class Id3v1Test extends MetadataTestCase
{
    /**
     * @var \GravityMedia\Metadata\SplFileInfo
     */
    protected $file;

    protected function setUp()
    {
        $source = 'tests/resource/notags.mp3';
        $target = tempnam(sys_get_temp_dir(), 'php');
        if (!copy($source, $target)) {
            throw new \RuntimeException(sprintf('Unable to create temporary file `%s` from `%s`', $source, $target));
        }
        $this->file = new SplFileInfo($target);
    }

    protected function tearDown()
    {
        unlink($this->file->getRealPath());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getTitle
     */
    public function testShouldWriteAndReadTitle()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1()->setTitle($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getTitle());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getArtist
     */
    public function testShouldWriteAndReadArtist()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1()->setArtist($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getArtist());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getAlbum
     */
    public function testShouldWriteAndReadAlbum()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1()->setAlbum($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getAlbum());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getYear
     */
    public function testShouldWriteAndReadYear()
    {
        $expected = mt_rand(1000, 9999);
        $this->file->getMetadata()->getId3v1()->setYear($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getYear());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getComment
     */
    public function testShouldWriteAndReadComment()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1()->setComment($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getComment());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getTrack
     */
    public function testShouldWriteAndReadTrack()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v1()->setTrack($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getTrack());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getGenre
     */
    public function testShouldWriteAndReadGenre()
    {
        $availableGenres = array_values(Id3v1Processor::ArrayOfGenres());
        $expected = $availableGenres[array_rand($availableGenres)];
        $this->file->getMetadata()->getId3v1()->setGenre($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1()->getGenre());
    }
}
