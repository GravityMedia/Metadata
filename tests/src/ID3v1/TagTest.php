<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Genre;
use GravityMedia\Metadata\ID3v1\Tag;
use GravityMedia\Metadata\ID3v1\Version;

/**
 * ID3v1 tag test class.
 *
 * @package GravityMedia\MetadataTest\ID3v1
 *
 * @covers  GravityMedia\Metadata\ID3v1\Tag
 * @uses    GravityMedia\Metadata\ID3v1\Version
 * @uses    GravityMedia\Metadata\ID3v1\Genre
 */
class TagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that creating a tag throws an exception on inalid version constructor argument
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid version
     */
    public function testCreatingTagThrowsExceptionOnInvalidVersionConstructorArgument()
    {
        new Tag(-1);
    }

    /**
     * Test that creating the default tag in default version
     */
    public function testCreatingDefaultTag()
    {
        $tag = new Tag();

        $this->assertEquals(Version::VERSION_11, $tag->getVersion());
    }

    /**
     * Test that setting title throws an exception on invalid artist arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Title argument exceeds maximum number of characters
     */
    public function testSettingTitleThrowsExceptionOnInvalidArtistArgument()
    {
        $tag = new Tag();

        $tag->setTitle(str_repeat('#', 31));
    }

    /**
     * Test that setting a valid title can be retrieved afterwards
     */
    public function testSettingValidTitle()
    {
        $title = str_repeat('#', 30);
        $tag = new Tag();
        $tag->setTitle($title);

        $this->assertEquals($title, $tag->getTitle());
    }

    /**
     * Test that setting artist throws an exception on invalid artist arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Artist argument exceeds maximum number of characters
     */
    public function testSettingArtistThrowsExceptionOnInvalidArtistArgument()
    {
        $tag = new Tag();

        $tag->setArtist(str_repeat('#', 31));
    }

    /**
     * Test that setting a valid artist can be retrieved afterwards
     */
    public function testSettingValidArtist()
    {
        $artist = str_repeat('#', 30);
        $tag = new Tag();
        $tag->setArtist($artist);

        $this->assertEquals($artist, $tag->getArtist());
    }

    /**
     * Test that setting album throws an exception on invalid album arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Album argument exceeds maximum number of characters
     */
    public function testSettingAlbumThrowsExceptionOnInvalidAlbumArgument()
    {
        $tag = new Tag();

        $tag->setAlbum(str_repeat('#', 31));
    }

    /**
     * Test that setting a valid album can be retrieved afterwards
     */
    public function testSettingValidAlbum()
    {
        $album = str_repeat('#', 30);
        $tag = new Tag();
        $tag->setAlbum($album);

        $this->assertEquals($album, $tag->getAlbum());
    }

    /**
     * Test that setting year throws an exception on invalid year arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Year argument must have exactly 4 digits
     */
    public function testSettingYearThrowsExceptionOnInvalidYearArgument()
    {
        $tag = new Tag();

        $tag->setYear(101);
    }

    /**
     * Test that setting a valid year can be retrieved afterwards
     */
    public function testSettingValidYear()
    {
        $year = 2003;
        $tag = new Tag();
        $tag->setYear($year);

        $this->assertEquals($year, $tag->getYear());
    }

    /**
     * Invalid comment data provider
     *
     * @return array
     */
    public function invalidCommentDataProvider()
    {
        return array(
            array(Version::VERSION_10, str_repeat('#', 31)),
            array(Version::VERSION_11, str_repeat('#', 29))
        );
    }

    /**
     * Test that setting comment throws an exception on invalid comment arguments
     *
     * @param int    $version
     * @param string $comment
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Comment argument exceeds maximum number of characters
     *
     * @dataProvider             invalidCommentDataProvider
     */
    public function testSettingCommentThrowsExceptionOnInvalidCommentArgument($version, $comment)
    {
        $tag = new Tag($version);

        $tag->setComment($comment);
    }

    /**
     * Valid comment data provider
     *
     * @return array
     */
    public function validCommentDataProvider()
    {
        return array(
            array(Version::VERSION_10, str_repeat('#', 30)),
            array(Version::VERSION_11, str_repeat('#', 28))
        );
    }

    /**
     * Test that setting a valid comment can be retrieved afterwards
     *
     * @param int    $version
     * @param string $comment
     *
     * @dataProvider validCommentDataProvider
     */
    public function testSettingValidComment($version, $comment)
    {
        $tag = new Tag($version);
        $tag->setComment($comment);

        $this->assertEquals($comment, $tag->getComment());
    }

    /**
     * Test that setting track throws an exception on ID3 v1.0 tag
     *
     * @expectedException \GravityMedia\Metadata\Exception\BadMethodCallException
     * @expectedExceptionMessage Track is not supported in this version
     */
    public function testSettingTrackThrowsExceptionOnID3v10Tag()
    {
        $tag = new Tag(Version::VERSION_10);

        $tag->setTrack(12);
    }

    /**
     * Test that setting track throws an exception on invalid track arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Track argument exceeds 2 digits
     */
    public function testSettingTrackThrowsExceptionOnInvalidTrackArgument()
    {
        $tag = new Tag();

        $tag->setTrack(1000);
    }

    /**
     * Test that setting a valid track can be retrieved afterwards
     */
    public function testSettingValidTrack()
    {
        $track = 12;
        $tag = new Tag();
        $tag->setTrack($track);

        $this->assertEquals($track, $tag->getTrack());
    }

    /**
     * Test that setting genre throws an exception on invalid genre arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid genre
     */
    public function testSettingGenreThrowsExceptionOnInvalidGenreArgument()
    {
        $tag = new Tag();

        $tag->setGenre(255);
    }

    /**
     * Test that setting a valid genre can be retrieved afterwards
     */
    public function testSettingValidGenre()
    {
        $genre = Genre::GENRE_HIP_HOP;
        $tag = new Tag();
        $tag->setGenre($genre);

        $this->assertEquals($genre, $tag->getGenre());
    }

    /**
     * Test that setting genre string throws an exception on invalid genre arguments
     *
     * @expectedException \GravityMedia\Metadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid genre
     */
    public function testSettingGenreStringThrowsExceptionOnInvalidGenreArgument()
    {
        $tag = new Tag();

        $tag->setGenreFromString('PHPUnit');
    }

    /**
     * Test that setting a valid genre string can be retrieved afterwards
     */
    public function testSettingValidGenreString()
    {
        $genre = 'Hip-Hop';
        $tag = new Tag();
        $tag->setGenreFromString($genre);

        $this->assertEquals($genre, $tag->getGenreAsString());
    }
}
