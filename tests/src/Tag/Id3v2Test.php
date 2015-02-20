<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\Tag;

use GravityMedia\Metadata\Feature\Picture;
use GravityMedia\Metadata\SplFileInfo;

/**
 * ID3 v2 tag test
 *
 * @package GravityMedia\MetadataTest\Tag
 */
class Id3v2Test extends TagTestCase
{
    /**
     * @var \GravityMedia\Metadata\SplFileInfo
     */
    protected $file;

    protected function setUp()
    {
        $source = 'tests/resource/tag/notags.mp3';
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
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getTitle
     */
    public function testShouldWriteAndReadTitle()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setTitle($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getTitle());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getArtist
     */
    public function testShouldWriteAndReadArtist()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setArtist($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getArtist());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getAlbum
     */
    public function testShouldWriteAndReadAlbum()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setAlbum($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getAlbum());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getYear
     */
    public function testShouldWriteAndReadYear()
    {
        $expected = mt_rand(1000, 9999);
        $this->file->getMetadata()->getId3v2Tag()->setYear($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getYear());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getComment
     */
    public function testShouldWriteAndReadComment()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setComment($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getComment());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getTrack
     */
    public function testShouldWriteAndReadTrack()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2Tag()->setTrack($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getTrack());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getTrackCount
     */
    public function testShouldWriteAndReadTrackCount()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2Tag()->setTrack(1)->setTrackCount($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getTrackCount());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getGenre
     */
    public function testShouldWriteAndReadGenre()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setGenre($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getGenre());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getBand
     */
    public function testShouldWriteAndReadBand()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setBand($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getBand());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getWorks
     */
    public function testShouldWriteAndReadWorks()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setWorks($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getWorks());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getComposer
     */
    public function testShouldWriteAndReadComposer()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setComposer($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getComposer());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getOriginalArtist
     */
    public function testShouldWriteAndReadOriginalArtist()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2Tag()->setOriginalArtist($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getOriginalArtist());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getDisc
     */
    public function testShouldWriteAndReadDisc()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2Tag()->setDisc($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getDisc());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getDiscCount
     */
    public function testShouldWriteAndReadDiscCount()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2Tag()->setDisc(1)->setDiscCount($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getDiscCount());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getPicture
     */
    public function testShouldWriteAndReadPicture()
    {
        $expected = new Picture();
        $expected->setData(file_get_contents('tests/resource/tag/image.png'));
        $expected->setMime('image/png');
        $expected->setPictureType('Other');
        $expected->setDescription('Cover');
        $this->file->getMetadata()->getId3v2Tag()->setPicture($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2Tag()->getPicture());
    }
}
