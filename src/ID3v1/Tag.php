<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception;
use GravityMedia\Metadata\TagInterface;

/**
 * ID3v1 tag
 *
 * @package GravityMedia\Metadata
 */
class Tag implements TagInterface
{
    /**
     * Tag version 1.0
     */
    const VERSION_10 = 0;

    /**
     * Tag version 1.1
     */
    const VERSION_11 = 1;

    /**
     * Supported genres
     *
     * @var array
     */
    public static $genres = array(
        // Core specification
        0 => 'Blues', 1 => 'Classic Rock', 2 => 'Country', 3 => 'Dance', 4 => 'Disco', 5 => 'Funk', 6 => 'Grunge',
        7 => 'Hip-Hop', 8 => 'Jazz', 9 => 'Metal', 10 => 'New Age', 11 => 'Oldies', 12 => 'Other', 13 => 'Pop',
        14 => 'R&B', 15 => 'Rap', 16 => 'Reggae', 17 => 'Rock', 18 => 'Techno', 19 => 'Industrial', 20 => 'Alternative',
        21 => 'Ska', 22 => 'Death Metal', 23 => 'Pranks', 24 => 'Soundtrack', 25 => 'Euro-Techno', 26 => 'Ambient',
        27 => 'Trip-Hop', 28 => 'Vocal', 29 => 'Jazz+Funk', 30 => 'Fusion', 31 => 'Trance', 32 => 'Classical',
        33 => 'Instrumental', 34 => 'Acid', 35 => 'House', 36 => 'Game', 37 => 'Sound Clip', 38 => 'Gospel',
        39 => 'Noise', 40 => 'Alt. Rock', 41 => 'Bass', 42 => 'Soul', 43 => 'Punk', 44 => 'Space', 45 => 'Meditative',
        46 => 'Instrumental Pop', 47 => 'Instrumental Rock', 48 => 'Ethnic', 49 => 'Gothic', 50 => 'Darkwave',
        51 => 'Techno-Industrial', 52 => 'Electronic', 53 => 'Pop-Folk', 54 => 'Eurodance', 55 => 'Dream',
        56 => 'Southern Rock', 57 => 'Comedy', 58 => 'Cult', 59 => 'Gangsta Rap', 60 => 'Top 40', 61 => 'Christian Rap',
        62 => 'Pop/Funk', 63 => 'Jungle', 64 => 'Native American', 65 => 'Cabaret', 66 => 'New Wave',
        67 => 'Psychedelic', 68 => 'Rave', 69 => 'Showtunes', 70 => 'Trailer', 71 => 'Lo-Fi', 72 => 'Tribal',
        73 => 'Acid Punk', 74 => 'Acid Jazz', 75 => 'Polka', 76 => 'Retro', 77 => 'Musical', 78 => 'Rock & Roll',
        79 => 'Hard Rock',
        // Winamp extension
        80 => 'Folk', 81 => 'Folk/Rock', 82 => 'National Folk', 83 => 'Swing', 84 => 'Fast-Fusion', 85 => 'Bebob',
        86 => 'Latin', 87 => 'Revival', 88 => 'Celtic', 89 => 'Bluegrass', 90 => 'Avantgarde', 91 => 'Gothic Rock',
        92 => 'Progressive Rock', 93 => 'Psychedelic Rock', 94 => 'Symphonic Rock', 95 => 'Slow Rock', 96 => 'Big Band',
        97 => 'Chorus', 98 => 'Easy Listening', 99 => 'Acoustic', 100 => 'Humour', 101 => 'Speech', 102 => 'Chanson',
        103 => 'Opera', 104 => 'Chamber Music', 105 => 'Sonata', 106 => 'Symphony', 107 => 'Booty Bass',
        108 => 'Primus', 109 => 'Porn Groove', 110 => 'Satire', 111 => 'Slow Jam', 112 => 'Club', 113 => 'Tango',
        114 => 'Samba', 115 => 'Folklore', 116 => 'Ballad', 117 => 'Power Ballad', 118 => 'Rhythmic Soul',
        119 => 'Freestyle', 120 => 'Duet', 121 => 'Punk Rock', 122 => 'Drum Solo', 123 => 'A Cappella',
        124 => 'Euro-House', 125 => 'Dance Hall', 126 => 'Goa', 127 => 'Drum & Bass', 128 => 'Club-House',
        129 => 'Hardcore', 130 => 'Terror', 131 => 'Indie', 132 => 'BritPop', 133 => 'Negerpunk', 134 => 'Polsk Punk',
        135 => 'Beat', 136 => 'Christian Gangsta Rap', 137 => 'Heavy Metal', 138 => 'Black Metal', 139 => 'Crossover',
        140 => 'Contemporary Christian', 141 => 'Christian Rock',
        // Since Winamp 1.91 (1998)
        142 => 'Merengue', 143 => 'Salsa', 144 => 'Thrash Metal', 145 => 'Anime', 146 => 'JPop', 147 => 'Synthpop',
        // Since Winamp 5.6
        148 => 'Abstract', 149 => 'Art Rock', 150 => 'Baroque', 151 => 'Bhangra', 152 => 'Big Beat', 153 => 'Breakbeat',
        154 => 'Chillout', 155 => 'Downtempo', 156 => 'Dub', 157 => 'EBM', 158 => 'Eclectic', 159 => 'Electro',
        160 => 'Electroclash', 161 => 'Emo', 162 => 'Experimental', 163 => 'Garage', 164 => 'Global', 165 => 'IDM',
        166 => 'Illbient', 167 => 'Industro-Goth', 168 => 'Jam Band', 169 => 'Krautrock', 170 => 'Leftfield',
        171 => 'Lounge', 172 => 'Math Rock', 173 => 'New Romantic', 174 => 'Nu-Breakz', 175 => 'Post-Punk',
        176 => 'Post-Rock', 177 => 'Psytrance', 178 => 'Shoegaze', 179 => 'Space Rock', 180 => 'Trop Rock',
        181 => 'World Music', 182 => 'Neoclassical', 183 => 'Audiobook', 184 => 'Audio Theatre',
        185 => 'Neue Deutsche Welle', 186 => 'Podcast', 187 => 'Indie Rock', 188 => 'G-Funk', 189 => 'Dubstep',
        190 => 'Garage Rock', 191 => 'Psybient'
    );

    /**
     * @var int
     */
    protected $version;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $artist;

    /**
     * @var string
     */
    protected $album;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var int
     */
    protected $track;

    /**
     * @var string
     */
    protected $genre;

    /**
     * Create ID3v1 tag object
     *
     * @param int $version The version (default is 1: v1.1)
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid version arguments
     */
    public function __construct($version = self::VERSION_11)
    {
        if (!in_array($version, array(self::VERSION_10, self::VERSION_11))) {
            throw new Exception\InvalidArgumentException('Invalid version argument');
        }

        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set title
     *
     * @param string $title The title
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the title exceeds 30 characters
     *
     * @return $this
     */
    public function setTitle($title)
    {
        if (strlen($title) > 30) {
            throw new Exception\InvalidArgumentException('Title argument exceeds maximum number of characters');
        }

        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set artist
     *
     * @param string $artist The artist
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the artist exceeds 30 characters
     *
     * @return $this
     */
    public function setArtist($artist)
    {
        if (strlen($artist) > 30) {
            throw new Exception\InvalidArgumentException('Artist argument exceeds maximum number of characters');
        }

        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set album
     *
     * @param string $album The album
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the album exceeds 30 characters
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        if (strlen($album) > 30) {
            throw new Exception\InvalidArgumentException('Album argument exceeds maximum number of characters');
        }

        $this->album = $album;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set year
     *
     * @param int $year The year
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the year does not have exactly 4 digits
     *
     * @return $this
     */
    public function setYear($year)
    {
        if (preg_match('/^\d{4}$/', $year) < 1) {
            throw new Exception\InvalidArgumentException('Year argument must have exactly 4 digits');
        }

        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set comment
     *
     * @param string $comment The comment
     *
     * @throws Exception\InvalidArgumentException An exception is thrown when the comment exceeds 28 characters
     *                                            (ID3 v1.1) or 30 characters (ID3 v1.0)
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $max = self::VERSION_11 === $this->version ? 28 : 30;
        if (strlen($comment) > $max) {
            throw new Exception\InvalidArgumentException('Comment argument exceeds maximum number of characters');
        }

        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set track number
     *
     * @param int $track The track number
     *
     * @throws Exception\BadMethodCallException An exception is thrown on ID3 v1.0 tag
     * @throws Exception\InvalidArgumentException An exception is thrown when the year does not have exactly 2 digits
     *
     * @return $this
     */
    public function setTrack($track)
    {
        if (self::VERSION_10 === $this->version) {
            throw new Exception\BadMethodCallException('Track is not supported in this version');
        }

        if (preg_match('/^\d{1,2}$/', $track) < 1) {
            throw new Exception\InvalidArgumentException('Track argument exceeds 2 digits');
        }

        $this->track = $track;

        return $this;
    }

    /**
     * Get track number
     *
     * @return int
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set genre
     *
     * @param string $genre The genre
     *
     * @throws Exception\InvalidArgumentException An exception is thrown on invalid genre arguments
     *
     * @return $this
     */
    public function setGenre($genre)
    {
        if (!in_array($genre, array_values(self::$genres))) {
            throw new Exception\InvalidArgumentException('Invalid genre argument');
        }

        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }
}
