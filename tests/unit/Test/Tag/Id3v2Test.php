<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Test\Tag;

use GravityMedia\Metadata\Feature\Picture;
use GravityMedia\Metadata\SplFileInfo;
use GravityMedia\Metadata\Test\MetadataTestCase;

/**
 * ID3 v2 tag test
 *
 * @package GravityMedia\Metadata\Test\Tag
 */
class Id3v2Test extends MetadataTestCase
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
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getTitle
     */
    public function testShouldWriteAndReadTitle()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setTitle($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getTitle());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getArtist
     */
    public function testShouldWriteAndReadArtist()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setArtist($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getArtist());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getAlbum
     */
    public function testShouldWriteAndReadAlbum()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setAlbum($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getAlbum());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getYear
     */
    public function testShouldWriteAndReadYear()
    {
        $expected = mt_rand(1000, 9999);
        $this->file->getMetadata()->getId3v2()->setYear($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getYear());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getComment
     */
    public function testShouldWriteAndReadComment()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setComment($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getComment());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getTrack
     */
    public function testShouldWriteAndReadTrack()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2()->setTrack($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getTrack());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getTrackCount
     */
    public function testShouldWriteAndReadTrackCount()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2()->setTrack(1)->setTrackCount($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getTrackCount());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getGenre
     */
    public function testShouldWriteAndReadGenre()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setGenre($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getGenre());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getBand
     */
    public function testShouldWriteAndReadBand()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setBand($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getBand());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getWorks
     */
    public function testShouldWriteAndReadWorks()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setWorks($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getWorks());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getComposer
     */
    public function testShouldWriteAndReadComposer()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setComposer($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getComposer());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getOriginalArtist
     */
    public function testShouldWriteAndReadOriginalArtist()
    {
        $expected = $this->generateRandomString(250);
        $this->file->getMetadata()->getId3v2()->setOriginalArtist($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getOriginalArtist());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getDisc
     */
    public function testShouldWriteAndReadDisc()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2()->setDisc($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getDisc());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getDiscCount
     */
    public function testShouldWriteAndReadDiscCount()
    {
        $expected = mt_rand(1, 99);
        $this->file->getMetadata()->getId3v2()->setDisc(1)->setDiscCount($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getDiscCount());
    }

    /**
     * @covers \GravityMedia\Metadata\Tag\Id3v2::getPicture
     */
    public function testShouldWriteAndReadPicture()
    {
        $expected = new Picture();
        $expected->setData(file_get_contents('tests/resource/image.png'));
        $expected->setMime('image/png');
        $expected->setPictureTypeId(0x00);
        $expected->setDescription('cover');
        $this->file->getMetadata()->getId3v2()->setPicture($expected)->save();

        $this->assertEquals($expected, $this->file->getMetadata()->getId3v2()->getPicture());
    }
}
