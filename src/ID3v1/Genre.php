<?php
/**
 * This file is part of the Metadata package.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

use GravityMedia\Metadata\Exception\InvalidArgumentException;
use GravityMedia\Metadata\ID3v1\Enum\Genre as GenreEnum;

/**
 * ID3v1 genre
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Genre
{
    /**
     * String representations
     *
     * @var string[]
     */
    protected static $strings = [
        GenreEnum::GENRE_BLUES => 'Blues',
        GenreEnum::GENRE_CLASSIC_ROCK => 'Classic Rock',
        GenreEnum::GENRE_COUNTRY => 'Country',
        GenreEnum::GENRE_DANCE => 'Dance',
        GenreEnum::GENRE_DISCO => 'Disco',
        GenreEnum::GENRE_FUNK => 'Funk',
        GenreEnum::GENRE_GRUNGE => 'Grunge',
        GenreEnum::GENRE_HIP_HOP => 'Hip-Hop',
        GenreEnum::GENRE_JAZZ => 'Jazz',
        GenreEnum::GENRE_METAL => 'Metal',
        GenreEnum::GENRE_NEW_AGE => 'New Age',
        GenreEnum::GENRE_OLDIES => 'Oldies',
        GenreEnum::GENRE_OTHER => 'Other',
        GenreEnum::GENRE_POP => 'Pop',
        GenreEnum::GENRE_R_AND_B => 'R&B',
        GenreEnum::GENRE_RAP => 'Rap',
        GenreEnum::GENRE_REGGAE => 'Reggae',
        GenreEnum::GENRE_ROCK => 'Rock',
        GenreEnum::GENRE_TECHNO => 'Techno',
        GenreEnum::GENRE_INDUSTRIAL => 'Industrial',
        GenreEnum::GENRE_ALTERNATIVE => 'Alternative',
        GenreEnum::GENRE_SKA => 'Ska',
        GenreEnum::GENRE_DEATH_METAL => 'Death Metal',
        GenreEnum::GENRE_PRANKS => 'Pranks',
        GenreEnum::GENRE_SOUNDTRACK => 'Soundtrack',
        GenreEnum::GENRE_EURO_TECHNO => 'Euro-Techno',
        GenreEnum::GENRE_AMBIENT => 'Ambient',
        GenreEnum::GENRE_TRIP_HOP => 'Trip-Hop',
        GenreEnum::GENRE_VOCAL => 'Vocal',
        GenreEnum::GENRE_JAZZ_AND_FUNK => 'Jazz+Funk',
        GenreEnum::GENRE_FUSION => 'Fusion',
        GenreEnum::GENRE_TRANCE => 'Trance',
        GenreEnum::GENRE_CLASSICAL => 'Classical',
        GenreEnum::GENRE_INSTRUMENTAL => 'Instrumental',
        GenreEnum::GENRE_ACID => 'Acid',
        GenreEnum::GENRE_HOUSE => 'House',
        GenreEnum::GENRE_GAME => 'Game',
        GenreEnum::GENRE_SOUND_CLIP => 'Sound Clip',
        GenreEnum::GENRE_GOSPEL => 'Gospel',
        GenreEnum::GENRE_NOISE => 'Noise',
        GenreEnum::GENRE_ALTERNATIVE_ROCK => 'Alt. Rock',
        GenreEnum::GENRE_BASS => 'Bass',
        GenreEnum::GENRE_SOUL => 'Soul',
        GenreEnum::GENRE_PUNK => 'Punk',
        GenreEnum::GENRE_SPACE => 'Space',
        GenreEnum::GENRE_MEDITATIVE => 'Meditative',
        GenreEnum::GENRE_INSTRUMENTAL_POP => 'Instrumental Pop',
        GenreEnum::GENRE_INSTRUMENTAL_ROCK => 'Instrumental Rock',
        GenreEnum::GENRE_ETHNIC => 'Ethnic',
        GenreEnum::GENRE_GOTHIC => 'Gothic',
        GenreEnum::GENRE_DARKWAVE => 'Darkwave',
        GenreEnum::GENRE_TECHNO_INDUSTRIAL => 'Techno-Industrial',
        GenreEnum::GENRE_ELECTRONIC => 'Electronic',
        GenreEnum::GENRE_POP_FOLK => 'Pop-Folk',
        GenreEnum::GENRE_EURODANCE => 'Eurodance',
        GenreEnum::GENRE_DREAM => 'Dream',
        GenreEnum::GENRE_SOUTHERN_ROCK => 'Southern Rock',
        GenreEnum::GENRE_COMEDY => 'Comedy',
        GenreEnum::GENRE_CULT => 'Cult',
        GenreEnum::GENRE_GANGSTA_RAP => 'Gangsta Rap',
        GenreEnum::GENRE_TOP_40 => 'Top 40',
        GenreEnum::GENRE_CHRISTIAN_RAP => 'Christian Rap',
        GenreEnum::GENRE_POP_OR_FUNK => 'Pop/Funk',
        GenreEnum::GENRE_JUNGLE => 'Jungle',
        GenreEnum::GENRE_NATIVE_AMERICAN => 'Native American',
        GenreEnum::GENRE_CABARET => 'Cabaret',
        GenreEnum::GENRE_NEW_WAVE => 'New Wave',
        GenreEnum::GENRE_PSYCHEDELIC => 'Psychedelic',
        GenreEnum::GENRE_RAVE => 'Rave',
        GenreEnum::GENRE_SHOWTUNES => 'Showtunes',
        GenreEnum::GENRE_TRAILER => 'Trailer',
        GenreEnum::GENRE_LO_FI => 'Lo-Fi',
        GenreEnum::GENRE_TRIBAL => 'Tribal',
        GenreEnum::GENRE_ACID_PUNK => 'Acid Punk',
        GenreEnum::GENRE_ACID_JAZZ => 'Acid Jazz',
        GenreEnum::GENRE_POLKA => 'Polka',
        GenreEnum::GENRE_RETRO => 'Retro',
        GenreEnum::GENRE_MUSICAL => 'Musical',
        GenreEnum::GENRE_ROCK_AND_ROLL => 'Rock & Roll',
        GenreEnum::GENRE_HARD_ROCK => 'Hard Rock',
        GenreEnum::GENRE_FOLK => 'Folk',
        GenreEnum::GENRE_FOLK_OR_ROCK => 'Folk/Rock',
        GenreEnum::GENRE_NATIONAL_FOLK => 'National Folk',
        GenreEnum::GENRE_SWING => 'Swing',
        GenreEnum::GENRE_FAST_FUSION => 'Fast-Fusion',
        GenreEnum::GENRE_BEBOB => 'Bebob',
        GenreEnum::GENRE_LATIN => 'Latin',
        GenreEnum::GENRE_REVIVAL => 'Revival',
        GenreEnum::GENRE_CELTIC => 'Celtic',
        GenreEnum::GENRE_BLUEGRASS => 'Bluegrass',
        GenreEnum::GENRE_AVANTGARDE => 'Avantgarde',
        GenreEnum::GENRE_GOTHIC_ROCK => 'Gothic Rock',
        GenreEnum::GENRE_PROGRESSIVE_ROCK => 'Progressive Rock',
        GenreEnum::GENRE_PSYCHEDELIC_ROCK => 'Psychedelic Rock',
        GenreEnum::GENRE_SYMPHONIC_ROCK => 'Symphonic Rock',
        GenreEnum::GENRE_SLOW_ROCK => 'Slow Rock',
        GenreEnum::GENRE_BIG_BAND => 'Big Band',
        GenreEnum::GENRE_CHORUS => 'Chorus',
        GenreEnum::GENRE_EASY_LISTENING => 'Easy Listening',
        GenreEnum::GENRE_ACOUSTIC => 'Acoustic',
        GenreEnum::GENRE_HUMOUR => 'Humour',
        GenreEnum::GENRE_SPEECH => 'Speech',
        GenreEnum::GENRE_CHANSON => 'Chanson',
        GenreEnum::GENRE_OPERA => 'Opera',
        GenreEnum::GENRE_CHAMBER_MUSIC => 'Chamber Music',
        GenreEnum::GENRE_SONATA => 'Sonata',
        GenreEnum::GENRE_SYMPHONY => 'Symphony',
        GenreEnum::GENRE_BOOTY_BASS => 'Booty Bass',
        GenreEnum::GENRE_PRIMUS => 'Primus',
        GenreEnum::GENRE_PORN_GROOVE => 'Porn Groove',
        GenreEnum::GENRE_SATIRE => 'Satire',
        GenreEnum::GENRE_SLOW_JAM => 'Slow Jam',
        GenreEnum::GENRE_CLUB => 'Club',
        GenreEnum::GENRE_TANGO => 'Tango',
        GenreEnum::GENRE_SAMBA => 'Samba',
        GenreEnum::GENRE_FOLKLORE => 'Folklore',
        GenreEnum::GENRE_BALLAD => 'Ballad',
        GenreEnum::GENRE_POWER_BALLAD => 'Power Ballad',
        GenreEnum::GENRE_RHYTHMIC_SOUL => 'Rhythmic Soul',
        GenreEnum::GENRE_FREESTYLE => 'Freestyle',
        GenreEnum::GENRE_DUET => 'Duet',
        GenreEnum::GENRE_PUNK_ROCK => 'Punk Rock',
        GenreEnum::GENRE_DRUM_SOLO => 'Drum Solo',
        GenreEnum::GENRE_A_CAPPELLA => 'A Cappella',
        GenreEnum::GENRE_EURO_HOUSE => 'Euro-House',
        GenreEnum::GENRE_DANCE_HALL => 'Dance Hall',
        GenreEnum::GENRE_GOA => 'Goa',
        GenreEnum::GENRE_DRUM_AND_BASS => 'Drum & Bass',
        GenreEnum::GENRE_CLUB_HOUSE => 'Club-House',
        GenreEnum::GENRE_HARDCORE => 'Hardcore',
        GenreEnum::GENRE_TERROR => 'Terror',
        GenreEnum::GENRE_INDIE => 'Indie',
        GenreEnum::GENRE_BRITPOP => 'BritPop',
        GenreEnum::GENRE_NEGERPUNK => 'Negerpunk',
        GenreEnum::GENRE_POLSK_PUNK => 'Polsk Punk',
        GenreEnum::GENRE_BEAT => 'Beat',
        GenreEnum::GENRE_CHRISTIAN_GANGSTA_RAP => 'Christian Gangsta Rap',
        GenreEnum::GENRE_HEAVY_METAL => 'Heavy Metal',
        GenreEnum::GENRE_BLACK_METAL => 'Black Metal',
        GenreEnum::GENRE_CROSSOVER => 'Crossover',
        GenreEnum::GENRE_CONTEMPORARY_CHRISTIAN => 'Contemporary Christian',
        GenreEnum::GENRE_CHRISTIAN_ROCK => 'Christian Rock',
        GenreEnum::GENRE_MERENGUE => 'Merengue',
        GenreEnum::GENRE_SALSA => 'Salsa',
        GenreEnum::GENRE_THRASH_METAL => 'Thrash Metal',
        GenreEnum::GENRE_ANIME => 'Anime',
        GenreEnum::GENRE_JPOP => 'JPop',
        GenreEnum::GENRE_SYNTHPOP => 'Synthpop',
        GenreEnum::GENRE_ABSTRACT => 'Abstract',
        GenreEnum::GENRE_ART_ROCK => 'Art Rock',
        GenreEnum::GENRE_BAROQUE => 'Baroque',
        GenreEnum::GENRE_BHANGRA => 'Bhangra',
        GenreEnum::GENRE_BIG_BEAT => 'Big Beat',
        GenreEnum::GENRE_BREAKBEAT => 'Breakbeat',
        GenreEnum::GENRE_CHILLOUT => 'Chillout',
        GenreEnum::GENRE_DOWNTEMPO => 'Downtempo',
        GenreEnum::GENRE_DUB => 'Dub',
        GenreEnum::GENRE_EBM => 'EBM',
        GenreEnum::GENRE_ECLECTIC => 'Eclectic',
        GenreEnum::GENRE_ELECTRO => 'Electro',
        GenreEnum::GENRE_ELECTROCLASH => 'Electroclash',
        GenreEnum::GENRE_EMO => 'Emo',
        GenreEnum::GENRE_EXPERIMENTAL => 'Experimental',
        GenreEnum::GENRE_GARAGE => 'Garage',
        GenreEnum::GENRE_GLOBAL => 'Global',
        GenreEnum::GENRE_IDM => 'IDM',
        GenreEnum::GENRE_ILLBIENT => 'Illbient',
        GenreEnum::GENRE_INDUSTRO_GOTH => 'Industro-Goth',
        GenreEnum::GENRE_JAM_BAND => 'Jam Band',
        GenreEnum::GENRE_KRAUTROCK => 'Krautrock',
        GenreEnum::GENRE_LEFTFIELD => 'Leftfield',
        GenreEnum::GENRE_LOUNGE => 'Lounge',
        GenreEnum::GENRE_MATH_ROCK => 'Math Rock',
        GenreEnum::GENRE_NEW_ROMANTIC => 'New Romantic',
        GenreEnum::GENRE_NU_BREAKZ => 'Nu-Breakz',
        GenreEnum::GENRE_POST_PUNK => 'Post-Punk',
        GenreEnum::GENRE_POST_ROCK => 'Post-Rock',
        GenreEnum::GENRE_PSYTRANCE => 'Psytrance',
        GenreEnum::GENRE_SHOEGAZE => 'Shoegaze',
        GenreEnum::GENRE_SPACE_ROCK => 'Space Rock',
        GenreEnum::GENRE_TROP_ROCK => 'Trop Rock',
        GenreEnum::GENRE_WORLD_MUSIC => 'World Music',
        GenreEnum::GENRE_NEOCLASSICAL => 'Neoclassical',
        GenreEnum::GENRE_AUDIOBOOK => 'Audiobook',
        GenreEnum::GENRE_AUDIO_THEATRE => 'Audio Theatre',
        GenreEnum::GENRE_NEUE_DEUTSCHE_WELLE => 'Neue Deutsche Welle',
        GenreEnum::GENRE_PODCAST => 'Podcast',
        GenreEnum::GENRE_INDIE_ROCK => 'Indie Rock',
        GenreEnum::GENRE_G_FUNK => 'G-Funk',
        GenreEnum::GENRE_DUBSTEP => 'Dubstep',
        GenreEnum::GENRE_GARAGE_ROCK => 'Garage Rock',
        GenreEnum::GENRE_PSYBIENT => 'Psybient'
    ];

    /**
     * @var int
     */
    protected $genre;

    /**
     * Create genre converter object.
     *
     * @throws InvalidArgumentException An exception is thrown on invalid genre arguments
     *
     * @param int $genre
     */
    public function __construct($genre)
    {
        if (!in_array($genre, GenreEnum::values())) {
            throw new InvalidArgumentException('Invalid genre.');
        }

        $this->genre = $genre;
    }

    /**
     * Create genre converter from string
     *
     * @param string $string
     *
     * @return $this
     */
    public static function fromString($string)
    {
        return new static(array_search($string, static::$strings, true));
    }

    /**
     * Get genre
     *
     * @return int
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Convert genre to string
     *
     * @return string
     */
    public function toString()
    {
        return static::$strings[$this->genre];
    }
}
