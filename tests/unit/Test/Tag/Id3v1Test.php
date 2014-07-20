<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Test\Tag;

use getid3_id3v1 as Id3v1Processor;
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
        $this->file->getMetadata()->getId3v1Tag()->setTitle($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getTitle());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getArtist
     */
    public function testShouldWriteAndReadArtist()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1Tag()->setArtist($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getArtist());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getAlbum
     */
    public function testShouldWriteAndReadAlbum()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1Tag()->setAlbum($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getAlbum());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getYear
     */
    public function testShouldWriteAndReadYear()
    {
        $expected = mt_rand(1000, 9999);
        $this->file->getMetadata()->getId3v1Tag()->setYear($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getYear());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getComment
     */
    public function testShouldWriteAndReadComment()
    {
        $expected = $this->generateRandomString(30);
        $this->file->getMetadata()->getId3v1Tag()->setComment($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getComment());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getTrack
     */
    public function testShouldWriteAndReadTrack()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v1Tag()->setTrack($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getTrack());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v1::getGenre
     */
    public function testShouldWriteAndReadGenre()
    {
        $availableGenres = array_values(Id3v1Processor::ArrayOfGenres());
        $expected = $availableGenres[array_rand($availableGenres)];
        $this->file->getMetadata()->getId3v1Tag()->setGenre($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v1Tag()->getGenre());
    }
}
