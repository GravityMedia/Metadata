<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception\BadMethodCallException;
use GravityMedia\Metadata\Exception\InvalidArgumentException;
use GravityMedia\Metadata\ID3v1\Enum\Genre;
use GravityMedia\Metadata\ID3v1\Enum\Version;

/**
 * ID3v1 tag class.
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Tag
{
    /**
     * String representations of genres
     *
     * @var string[]
     */
    protected static $genres = [
        Genre::GENRE_BLUES => 'Blues',
        Genre::GENRE_CLASSIC_ROCK => 'Classic Rock',
        Genre::GENRE_COUNTRY => 'Country',
        Genre::GENRE_DANCE => 'Dance',
        Genre::GENRE_DISCO => 'Disco',
        Genre::GENRE_FUNK => 'Funk',
        Genre::GENRE_GRUNGE => 'Grunge',
        Genre::GENRE_HIP_HOP => 'Hip-Hop',
        Genre::GENRE_JAZZ => 'Jazz',
        Genre::GENRE_METAL => 'Metal',
        Genre::GENRE_NEW_AGE => 'New Age',
        Genre::GENRE_OLDIES => 'Oldies',
        Genre::GENRE_OTHER => 'Other',
        Genre::GENRE_POP => 'Pop',
        Genre::GENRE_R_AND_B => 'R&B',
        Genre::GENRE_RAP => 'Rap',
        Genre::GENRE_REGGAE => 'Reggae',
        Genre::GENRE_ROCK => 'Rock',
        Genre::GENRE_TECHNO => 'Techno',
        Genre::GENRE_INDUSTRIAL => 'Industrial',
        Genre::GENRE_ALTERNATIVE => 'Alternative',
        Genre::GENRE_SKA => 'Ska',
        Genre::GENRE_DEATH_METAL => 'Death Metal',
        Genre::GENRE_PRANKS => 'Pranks',
        Genre::GENRE_SOUNDTRACK => 'Soundtrack',
        Genre::GENRE_EURO_TECHNO => 'Euro-Techno',
        Genre::GENRE_AMBIENT => 'Ambient',
        Genre::GENRE_TRIP_HOP => 'Trip-Hop',
        Genre::GENRE_VOCAL => 'Vocal',
        Genre::GENRE_JAZZ_AND_FUNK => 'Jazz+Funk',
        Genre::GENRE_FUSION => 'Fusion',
        Genre::GENRE_TRANCE => 'Trance',
        Genre::GENRE_CLASSICAL => 'Classical',
        Genre::GENRE_INSTRUMENTAL => 'Instrumental',
        Genre::GENRE_ACID => 'Acid',
        Genre::GENRE_HOUSE => 'House',
        Genre::GENRE_GAME => 'Game',
        Genre::GENRE_SOUND_CLIP => 'Sound Clip',
        Genre::GENRE_GOSPEL => 'Gospel',
        Genre::GENRE_NOISE => 'Noise',
        Genre::GENRE_ALTERNATIVE_ROCK => 'Alt. Rock',
        Genre::GENRE_BASS => 'Bass',
        Genre::GENRE_SOUL => 'Soul',
        Genre::GENRE_PUNK => 'Punk',
        Genre::GENRE_SPACE => 'Space',
        Genre::GENRE_MEDITATIVE => 'Meditative',
        Genre::GENRE_INSTRUMENTAL_POP => 'Instrumental Pop',
        Genre::GENRE_INSTRUMENTAL_ROCK => 'Instrumental Rock',
        Genre::GENRE_ETHNIC => 'Ethnic',
        Genre::GENRE_GOTHIC => 'Gothic',
        Genre::GENRE_DARKWAVE => 'Darkwave',
        Genre::GENRE_TECHNO_INDUSTRIAL => 'Techno-Industrial',
        Genre::GENRE_ELECTRONIC => 'Electronic',
        Genre::GENRE_POP_FOLK => 'Pop-Folk',
        Genre::GENRE_EURODANCE => 'Eurodance',
        Genre::GENRE_DREAM => 'Dream',
        Genre::GENRE_SOUTHERN_ROCK => 'Southern Rock',
        Genre::GENRE_COMEDY => 'Comedy',
        Genre::GENRE_CULT => 'Cult',
        Genre::GENRE_GANGSTA_RAP => 'Gangsta Rap',
        Genre::GENRE_TOP_40 => 'Top 40',
        Genre::GENRE_CHRISTIAN_RAP => 'Christian Rap',
        Genre::GENRE_POP_OR_FUNK => 'Pop/Funk',
        Genre::GENRE_JUNGLE => 'Jungle',
        Genre::GENRE_NATIVE_AMERICAN => 'Native American',
        Genre::GENRE_CABARET => 'Cabaret',
        Genre::GENRE_NEW_WAVE => 'New Wave',
        Genre::GENRE_PSYCHEDELIC => 'Psychedelic',
        Genre::GENRE_RAVE => 'Rave',
        Genre::GENRE_SHOWTUNES => 'Showtunes',
        Genre::GENRE_TRAILER => 'Trailer',
        Genre::GENRE_LO_FI => 'Lo-Fi',
        Genre::GENRE_TRIBAL => 'Tribal',
        Genre::GENRE_ACID_PUNK => 'Acid Punk',
        Genre::GENRE_ACID_JAZZ => 'Acid Jazz',
        Genre::GENRE_POLKA => 'Polka',
        Genre::GENRE_RETRO => 'Retro',
        Genre::GENRE_MUSICAL => 'Musical',
        Genre::GENRE_ROCK_AND_ROLL => 'Rock & Roll',
        Genre::GENRE_HARD_ROCK => 'Hard Rock',
        Genre::GENRE_FOLK => 'Folk',
        Genre::GENRE_FOLK_OR_ROCK => 'Folk/Rock',
        Genre::GENRE_NATIONAL_FOLK => 'National Folk',
        Genre::GENRE_SWING => 'Swing',
        Genre::GENRE_FAST_FUSION => 'Fast-Fusion',
        Genre::GENRE_BEBOB => 'Bebob',
        Genre::GENRE_LATIN => 'Latin',
        Genre::GENRE_REVIVAL => 'Revival',
        Genre::GENRE_CELTIC => 'Celtic',
        Genre::GENRE_BLUEGRASS => 'Bluegrass',
        Genre::GENRE_AVANTGARDE => 'Avantgarde',
        Genre::GENRE_GOTHIC_ROCK => 'Gothic Rock',
        Genre::GENRE_PROGRESSIVE_ROCK => 'Progressive Rock',
        Genre::GENRE_PSYCHEDELIC_ROCK => 'Psychedelic Rock',
        Genre::GENRE_SYMPHONIC_ROCK => 'Symphonic Rock',
        Genre::GENRE_SLOW_ROCK => 'Slow Rock',
        Genre::GENRE_BIG_BAND => 'Big Band',
        Genre::GENRE_CHORUS => 'Chorus',
        Genre::GENRE_EASY_LISTENING => 'Easy Listening',
        Genre::GENRE_ACOUSTIC => 'Acoustic',
        Genre::GENRE_HUMOUR => 'Humour',
        Genre::GENRE_SPEECH => 'Speech',
        Genre::GENRE_CHANSON => 'Chanson',
        Genre::GENRE_OPERA => 'Opera',
        Genre::GENRE_CHAMBER_MUSIC => 'Chamber Music',
        Genre::GENRE_SONATA => 'Sonata',
        Genre::GENRE_SYMPHONY => 'Symphony',
        Genre::GENRE_BOOTY_BASS => 'Booty Bass',
        Genre::GENRE_PRIMUS => 'Primus',
        Genre::GENRE_PORN_GROOVE => 'Porn Groove',
        Genre::GENRE_SATIRE => 'Satire',
        Genre::GENRE_SLOW_JAM => 'Slow Jam',
        Genre::GENRE_CLUB => 'Club',
        Genre::GENRE_TANGO => 'Tango',
        Genre::GENRE_SAMBA => 'Samba',
        Genre::GENRE_FOLKLORE => 'Folklore',
        Genre::GENRE_BALLAD => 'Ballad',
        Genre::GENRE_POWER_BALLAD => 'Power Ballad',
        Genre::GENRE_RHYTHMIC_SOUL => 'Rhythmic Soul',
        Genre::GENRE_FREESTYLE => 'Freestyle',
        Genre::GENRE_DUET => 'Duet',
        Genre::GENRE_PUNK_ROCK => 'Punk Rock',
        Genre::GENRE_DRUM_SOLO => 'Drum Solo',
        Genre::GENRE_A_CAPPELLA => 'A Cappella',
        Genre::GENRE_EURO_HOUSE => 'Euro-House',
        Genre::GENRE_DANCE_HALL => 'Dance Hall',
        Genre::GENRE_GOA => 'Goa',
        Genre::GENRE_DRUM_AND_BASS => 'Drum & Bass',
        Genre::GENRE_CLUB_HOUSE => 'Club-House',
        Genre::GENRE_HARDCORE => 'Hardcore',
        Genre::GENRE_TERROR => 'Terror',
        Genre::GENRE_INDIE => 'Indie',
        Genre::GENRE_BRITPOP => 'BritPop',
        Genre::GENRE_NEGERPUNK => 'Negerpunk',
        Genre::GENRE_POLSK_PUNK => 'Polsk Punk',
        Genre::GENRE_BEAT => 'Beat',
        Genre::GENRE_CHRISTIAN_GANGSTA_RAP => 'Christian Gangsta Rap',
        Genre::GENRE_HEAVY_METAL => 'Heavy Metal',
        Genre::GENRE_BLACK_METAL => 'Black Metal',
        Genre::GENRE_CROSSOVER => 'Crossover',
        Genre::GENRE_CONTEMPORARY_CHRISTIAN => 'Contemporary Christian',
        Genre::GENRE_CHRISTIAN_ROCK => 'Christian Rock',
        Genre::GENRE_MERENGUE => 'Merengue',
        Genre::GENRE_SALSA => 'Salsa',
        Genre::GENRE_THRASH_METAL => 'Thrash Metal',
        Genre::GENRE_ANIME => 'Anime',
        Genre::GENRE_JPOP => 'JPop',
        Genre::GENRE_SYNTHPOP => 'Synthpop',
        Genre::GENRE_ABSTRACT => 'Abstract',
        Genre::GENRE_ART_ROCK => 'Art Rock',
        Genre::GENRE_BAROQUE => 'Baroque',
        Genre::GENRE_BHANGRA => 'Bhangra',
        Genre::GENRE_BIG_BEAT => 'Big Beat',
        Genre::GENRE_BREAKBEAT => 'Breakbeat',
        Genre::GENRE_CHILLOUT => 'Chillout',
        Genre::GENRE_DOWNTEMPO => 'Downtempo',
        Genre::GENRE_DUB => 'Dub',
        Genre::GENRE_EBM => 'EBM',
        Genre::GENRE_ECLECTIC => 'Eclectic',
        Genre::GENRE_ELECTRO => 'Electro',
        Genre::GENRE_ELECTROCLASH => 'Electroclash',
        Genre::GENRE_EMO => 'Emo',
        Genre::GENRE_EXPERIMENTAL => 'Experimental',
        Genre::GENRE_GARAGE => 'Garage',
        Genre::GENRE_GLOBAL => 'Global',
        Genre::GENRE_IDM => 'IDM',
        Genre::GENRE_ILLBIENT => 'Illbient',
        Genre::GENRE_INDUSTRO_GOTH => 'Industro-Goth',
        Genre::GENRE_JAM_BAND => 'Jam Band',
        Genre::GENRE_KRAUTROCK => 'Krautrock',
        Genre::GENRE_LEFTFIELD => 'Leftfield',
        Genre::GENRE_LOUNGE => 'Lounge',
        Genre::GENRE_MATH_ROCK => 'Math Rock',
        Genre::GENRE_NEW_ROMANTIC => 'New Romantic',
        Genre::GENRE_NU_BREAKZ => 'Nu-Breakz',
        Genre::GENRE_POST_PUNK => 'Post-Punk',
        Genre::GENRE_POST_ROCK => 'Post-Rock',
        Genre::GENRE_PSYTRANCE => 'Psytrance',
        Genre::GENRE_SHOEGAZE => 'Shoegaze',
        Genre::GENRE_SPACE_ROCK => 'Space Rock',
        Genre::GENRE_TROP_ROCK => 'Trop Rock',
        Genre::GENRE_WORLD_MUSIC => 'World Music',
        Genre::GENRE_NEOCLASSICAL => 'Neoclassical',
        Genre::GENRE_AUDIOBOOK => 'Audiobook',
        Genre::GENRE_AUDIO_THEATRE => 'Audio Theatre',
        Genre::GENRE_NEUE_DEUTSCHE_WELLE => 'Neue Deutsche Welle',
        Genre::GENRE_PODCAST => 'Podcast',
        Genre::GENRE_INDIE_ROCK => 'Indie Rock',
        Genre::GENRE_G_FUNK => 'G-Funk',
        Genre::GENRE_DUBSTEP => 'Dubstep',
        Genre::GENRE_GARAGE_ROCK => 'Garage Rock',
        Genre::GENRE_PSYBIENT => 'Psybient'
    ];

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
     * @var int
     */
    protected $genre;

    /**
     * Create tag object.
     *
     * @param int $version The version (default is 1: v1.1).
     *
     * @throws InvalidArgumentException An exception is thrown on invalid version arguments.
     */
    public function __construct($version = Version::VERSION_11)
    {
        if (!in_array($version, Version::values())) {
            throw new InvalidArgumentException('Invalid version');
        }

        $this->version = $version;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title The title.
     *
     * @throws InvalidArgumentException An exception is thrown when the title exceeds 30 characters.
     *
     * @return $this
     */
    public function setTitle($title)
    {
        if (strlen($title) > 30) {
            throw new InvalidArgumentException('Title argument exceeds maximum number of characters');
        }

        $this->title = $title;

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
     * Set artist.
     *
     * @param string $artist The artist.
     *
     * @throws InvalidArgumentException An exception is thrown when the artist exceeds 30 characters.
     *
     * @return $this
     */
    public function setArtist($artist)
    {
        if (strlen($artist) > 30) {
            throw new InvalidArgumentException('Artist argument exceeds maximum number of characters');
        }

        $this->artist = $artist;

        return $this;
    }

    /**
     * Get album
     *
     * @return string
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set album.
     *
     * @param string $album The album.
     *
     * @throws InvalidArgumentException An exception is thrown when the album exceeds 30 characters.
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        if (strlen($album) > 30) {
            throw new InvalidArgumentException('Album argument exceeds maximum number of characters');
        }

        $this->album = $album;

        return $this;
    }

    /**
     * Get year.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year.
     *
     * @param int $year The year.
     *
     * @throws InvalidArgumentException An exception is thrown when the year does not have exactly 4 digits.
     *
     * @return $this
     */
    public function setYear($year)
    {
        if (preg_match('/^\d{4}$/', $year) < 1) {
            throw new InvalidArgumentException('Year argument must have exactly 4 digits');
        }

        $this->year = $year;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment.
     *
     * @param string $comment The comment.
     *
     * @throws InvalidArgumentException An exception is thrown when the comment exceeds 28 characters (ID3 v1.1) or 30
     *                                  characters (ID3 v1.0).
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $max = Version::VERSION_11 === $this->version ? 28 : 30;
        if (strlen($comment) > $max) {
            throw new InvalidArgumentException('Comment argument exceeds maximum number of characters');
        }

        $this->comment = $comment;

        return $this;
    }

    /**
     * Get track number.
     *
     * @return int
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set track number.
     *
     * @param int $track The track number.
     *
     * @throws BadMethodCallException An exception is thrown on ID3 v1.0 tags.
     * @throws InvalidArgumentException An exception is thrown when the year does not have exactly 2 digits.
     *
     * @return $this
     */
    public function setTrack($track)
    {
        if (Version::VERSION_10 === $this->version) {
            throw new BadMethodCallException('Track is not supported in this version');
        }

        if (preg_match('/^\d{1,2}$/', $track) < 1) {
            throw new InvalidArgumentException('Track argument exceeds 2 digits');
        }

        $this->track = $track;

        return $this;
    }

    /**
     * Get genre number.
     *
     * @return int
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set genre number.
     *
     * @param int $genre The genre number.
     *
     * @throws InvalidArgumentException An exception is thrown on invalid genre arguments.
     *
     * @return $this
     */
    public function setGenre($genre)
    {
        if (!in_array($genre, Genre::values())) {
            throw new InvalidArgumentException('Invalid genre');
        }

        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre as string.
     *
     * @return string
     */
    public function getGenreAsString()
    {
        return static::$genres[$this->genre];
    }

    /**
     * Set genre from string.
     *
     * @param string $genre The genre string.
     *
     * @throws InvalidArgumentException An exception is thrown on invalid genre arguments.
     *
     * @return $this
     */
    public function setGenreFromString($genre)
    {
        return $this->setGenre(array_search($genre, static::$genres, true));
    }
}
