<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v1;

/**
 * ID3v1 genre class.
 *
 * @package GravityMedia\Metadata\ID3v1
 */
class Genre
{
    /**
     * Blues.
     */
    const GENRE_BLUES = 0;

    /**
     * Classic Rock.
     */
    const GENRE_CLASSIC_ROCK = 1;

    /**
     * Country.
     */
    const GENRE_COUNTRY = 2;

    /**
     * Dance.
     */
    const GENRE_DANCE = 3;

    /**
     * Disco.
     */
    const GENRE_DISCO = 4;

    /**
     * Funk.
     */
    const GENRE_FUNK = 5;

    /**
     * Grunge.
     */
    const GENRE_GRUNGE = 6;

    /**
     * Hip-Hop.
     */
    const GENRE_HIP_HOP = 7;

    /**
     * Jazz.
     */
    const GENRE_JAZZ = 8;

    /**
     * Metal.
     */
    const GENRE_METAL = 9;

    /**
     * New Age.
     */
    const GENRE_NEW_AGE = 10;

    /**
     * Oldies.
     */
    const GENRE_OLDIES = 11;

    /**
     * Other.
     */
    const GENRE_OTHER = 12;

    /**
     * Pop.
     */
    const GENRE_POP = 13;

    /**
     * R&B.
     */
    const GENRE_R_AND_B = 14;

    /**
     * Rap.
     */
    const GENRE_RAP = 15;

    /**
     * Reggae.
     */
    const GENRE_REGGAE = 16;

    /**
     * Rock.
     */
    const GENRE_ROCK = 17;

    /**
     * Techno.
     */
    const GENRE_TECHNO = 18;

    /**
     * Industrial.
     */
    const GENRE_INDUSTRIAL = 19;

    /**
     * Alternative.
     */
    const GENRE_ALTERNATIVE = 20;

    /**
     * Ska.
     */
    const GENRE_SKA = 21;

    /**
     * Death Metal.
     */
    const GENRE_DEATH_METAL = 22;

    /**
     * Pranks.
     */
    const GENRE_PRANKS = 23;

    /**
     * Soundtrack.
     */
    const GENRE_SOUNDTRACK = 24;

    /**
     * Euro-Techno.
     */
    const GENRE_EURO_TECHNO = 25;

    /**
     * Ambient.
     */
    const GENRE_AMBIENT = 26;

    /**
     * Trip-Hop.
     */
    const GENRE_TRIP_HOP = 27;

    /**
     * Vocal.
     */
    const GENRE_VOCAL = 28;

    /**
     * Jazz+Funk.
     */
    const GENRE_JAZZ_AND_FUNK = 29;

    /**
     * Fusion.
     */
    const GENRE_FUSION = 30;

    /**
     * Trance.
     */
    const GENRE_TRANCE = 31;

    /**
     * Classical.
     */
    const GENRE_CLASSICAL = 32;

    /**
     * Instrumental.
     */
    const GENRE_INSTRUMENTAL = 33;

    /**
     * Acid.
     */
    const GENRE_ACID = 34;

    /**
     * House.
     */
    const GENRE_HOUSE = 35;

    /**
     * Game.
     */
    const GENRE_GAME = 36;

    /**
     * Sound Clip.
     */
    const GENRE_SOUND_CLIP = 37;

    /**
     * Gospel.
     */
    const GENRE_GOSPEL = 38;

    /**
     * Noise.
     */
    const GENRE_NOISE = 39;

    /**
     * Alt. Rock.
     */
    const GENRE_ALTERNATIVE_ROCK = 40;

    /**
     * Bass.
     */
    const GENRE_BASS = 41;

    /**
     * Soul.
     */
    const GENRE_SOUL = 42;

    /**
     * Punk.
     */
    const GENRE_PUNK = 43;

    /**
     * Space.
     */
    const GENRE_SPACE = 44;

    /**
     * Meditative.
     */
    const GENRE_MEDITATIVE = 45;

    /**
     * Instrumental Pop.
     */
    const GENRE_INSTRUMENTAL_POP = 46;

    /**
     * Instrumental Rock.
     */
    const GENRE_INSTRUMENTAL_ROCK = 47;

    /**
     * Ethnic.
     */
    const GENRE_ETHNIC = 48;

    /**
     * Gothic.
     */
    const GENRE_GOTHIC = 49;

    /**
     * Darkwave.
     */
    const GENRE_DARKWAVE = 50;

    /**
     * Techno-Industrial.
     */
    const GENRE_TECHNO_INDUSTRIAL = 51;

    /**
     * Electronic.
     */
    const GENRE_ELECTRONIC = 52;

    /**
     * Pop-Folk.
     */
    const GENRE_POP_FOLK = 53;

    /**
     * Eurodance.
     */
    const GENRE_EURODANCE = 54;

    /**
     * Dream.
     */
    const GENRE_DREAM = 55;

    /**
     * Southern Rock.
     */
    const GENRE_SOUTHERN_ROCK = 56;

    /**
     * Comedy.
     */
    const GENRE_COMEDY = 57;

    /**
     * Cult.
     */
    const GENRE_CULT = 58;

    /**
     * Gangsta Rap.
     */
    const GENRE_GANGSTA_RAP = 59;

    /**
     * Top 40.
     */
    const GENRE_TOP_40 = 60;

    /**
     * Christian Rap.
     */
    const GENRE_CHRISTIAN_RAP = 61;

    /**
     * Pop/Funk.
     */
    const GENRE_POP_OR_FUNK = 62;

    /**
     * Jungle.
     */
    const GENRE_JUNGLE = 63;

    /**
     * Native American.
     */
    const GENRE_NATIVE_AMERICAN = 64;

    /**
     * Cabaret.
     */
    const GENRE_CABARET = 65;

    /**
     * New Wave.
     */
    const GENRE_NEW_WAVE = 66;

    /**
     * Psychedelic.
     */
    const GENRE_PSYCHEDELIC = 67;

    /**
     * Rave.
     */
    const GENRE_RAVE = 68;

    /**
     * Showtunes.
     */
    const GENRE_SHOWTUNES = 69;

    /**
     * Trailer.
     */
    const GENRE_TRAILER = 70;

    /**
     * Lo-Fi.
     */
    const GENRE_LO_FI = 71;

    /**
     * Tribal.
     */
    const GENRE_TRIBAL = 72;

    /**
     * Acid Punk.
     */
    const GENRE_ACID_PUNK = 73;

    /**
     * Acid Jazz.
     */
    const GENRE_ACID_JAZZ = 74;

    /**
     * Polka.
     */
    const GENRE_POLKA = 75;

    /**
     * Retro.
     */
    const GENRE_RETRO = 76;

    /**
     * Musical.
     */
    const GENRE_MUSICAL = 77;

    /**
     * Rock & Roll.
     */
    const GENRE_ROCK_AND_ROLL = 78;

    /**
     * Hard Rock.
     */
    const GENRE_HARD_ROCK = 79;

    /**
     * Folk.
     */
    const GENRE_FOLK = 80;

    /**
     * Folk/Rock.
     */
    const GENRE_FOLK_OR_ROCK = 81;

    /**
     * National Folk.
     */
    const GENRE_NATIONAL_FOLK = 82;

    /**
     * Swing.
     */
    const GENRE_SWING = 83;

    /**
     * Fast-Fusion.
     */
    const GENRE_FAST_FUSION = 84;

    /**
     * Bebob.
     */
    const GENRE_BEBOB = 85;

    /**
     * Latin.
     */
    const GENRE_LATIN = 86;

    /**
     * Revival.
     */
    const GENRE_REVIVAL = 87;

    /**
     * Celtic.
     */
    const GENRE_CELTIC = 88;

    /**
     * Bluegrass.
     */
    const GENRE_BLUEGRASS = 89;

    /**
     * Avantgarde.
     */
    const GENRE_AVANTGARDE = 90;

    /**
     * Gothic Rock.
     */
    const GENRE_GOTHIC_ROCK = 91;

    /**
     * Progressive Rock.
     */
    const GENRE_PROGRESSIVE_ROCK = 92;

    /**
     * Psychedelic Rock.
     */
    const GENRE_PSYCHEDELIC_ROCK = 93;

    /**
     * Symphonic Rock.
     */
    const GENRE_SYMPHONIC_ROCK = 94;

    /**
     * Slow Rock.
     */
    const GENRE_SLOW_ROCK = 95;

    /**
     * Big Band.
     */
    const GENRE_BIG_BAND = 96;

    /**
     * Chorus.
     */
    const GENRE_CHORUS = 97;

    /**
     * Easy Listening.
     */
    const GENRE_EASY_LISTENING = 98;

    /**
     * Acoustic.
     */
    const GENRE_ACOUSTIC = 99;

    /**
     * Humour.
     */
    const GENRE_HUMOUR = 100;

    /**
     * Speech.
     */
    const GENRE_SPEECH = 101;

    /**
     * Chanson.
     */
    const GENRE_CHANSON = 102;

    /**
     * Opera.
     */
    const GENRE_OPERA = 103;

    /**
     * Chamber Music.
     */
    const GENRE_CHAMBER_MUSIC = 104;

    /**
     * Sonata.
     */
    const GENRE_SONATA = 105;

    /**
     * Symphony.
     */
    const GENRE_SYMPHONY = 106;

    /**
     * Booty Bass.
     */
    const GENRE_BOOTY_BASS = 107;

    /**
     * Primus.
     */
    const GENRE_PRIMUS = 108;

    /**
     * Porn Groove.
     */
    const GENRE_PORN_GROOVE = 109;

    /**
     * Satire.
     */
    const GENRE_SATIRE = 110;

    /**
     * Slow Jam.
     */
    const GENRE_SLOW_JAM = 111;

    /**
     * Club.
     */
    const GENRE_CLUB = 112;

    /**
     * Tango.
     */
    const GENRE_TANGO = 113;

    /**
     * Samba.
     */
    const GENRE_SAMBA = 114;

    /**
     * Folklore.
     */
    const GENRE_FOLKLORE = 115;

    /**
     * Ballad.
     */
    const GENRE_BALLAD = 116;

    /**
     * Power Ballad.
     */
    const GENRE_POWER_BALLAD = 117;

    /**
     * Rhythmic Soul.
     */
    const GENRE_RHYTHMIC_SOUL = 118;

    /**
     * Freestyle.
     */
    const GENRE_FREESTYLE = 119;

    /**
     * Duet.
     */
    const GENRE_DUET = 120;

    /**
     * Punk Rock.
     */
    const GENRE_PUNK_ROCK = 121;

    /**
     * Drum Solo.
     */
    const GENRE_DRUM_SOLO = 122;

    /**
     * A Cappella.
     */
    const GENRE_A_CAPPELLA = 123;

    /**
     * Euro-House.
     */
    const GENRE_EURO_HOUSE = 124;

    /**
     * Dance Hall.
     */
    const GENRE_DANCE_HALL = 125;

    /**
     * Goa.
     */
    const GENRE_GOA = 126;

    /**
     * Drum & Bass.
     */
    const GENRE_DRUM_AND_BASS = 127;

    /**
     * Club-House.
     */
    const GENRE_CLUB_HOUSE = 128;

    /**
     * Hardcore.
     */
    const GENRE_HARDCORE = 129;

    /**
     * Terror.
     */
    const GENRE_TERROR = 130;

    /**
     * Indie.
     */
    const GENRE_INDIE = 131;

    /**
     * BritPop.
     */
    const GENRE_BRITPOP = 132;

    /**
     * Negerpunk.
     */
    const GENRE_NEGERPUNK = 133;

    /**
     * Polsk Punk.
     */
    const GENRE_POLSK_PUNK = 134;

    /**
     * Beat.
     */
    const GENRE_BEAT = 135;

    /**
     * Christian Gangsta Rap.
     */
    const GENRE_CHRISTIAN_GANGSTA_RAP = 136;

    /**
     * Heavy Metal.
     */
    const GENRE_HEAVY_METAL = 137;

    /**
     * Black Metal.
     */
    const GENRE_BLACK_METAL = 138;

    /**
     * Crossover.
     */
    const GENRE_CROSSOVER = 139;

    /**
     * Contemporary Christian.
     */
    const GENRE_CONTEMPORARY_CHRISTIAN = 140;

    /**
     * Christian Rock.
     */
    const GENRE_CHRISTIAN_ROCK = 141;

    /**
     * Merengue.
     */
    const GENRE_MERENGUE = 142;

    /**
     * Salsa.
     */
    const GENRE_SALSA = 143;

    /**
     * Thrash Metal.
     */
    const GENRE_THRASH_METAL = 144;

    /**
     * Anime.
     */
    const GENRE_ANIME = 145;

    /**
     * JPop.
     */
    const GENRE_JPOP = 146;

    /**
     * Synthpop.
     */
    const GENRE_SYNTHPOP = 147;

    /**
     * Abstract.
     */
    const GENRE_ABSTRACT = 148;

    /**
     * Art Rock.
     */
    const GENRE_ART_ROCK = 149;

    /**
     * Baroque.
     */
    const GENRE_BAROQUE = 150;

    /**
     * Bhangra.
     */
    const GENRE_BHANGRA = 151;

    /**
     * Big Beat.
     */
    const GENRE_BIG_BEAT = 152;

    /**
     * Breakbeat.
     */
    const GENRE_BREAKBEAT = 153;

    /**
     * Chillout.
     */
    const GENRE_CHILLOUT = 154;

    /**
     * Downtempo.
     */
    const GENRE_DOWNTEMPO = 155;

    /**
     * Dub.
     */
    const GENRE_DUB = 156;

    /**
     * EBM.
     */
    const GENRE_EBM = 157;

    /**
     * Eclectic.
     */
    const GENRE_ECLECTIC = 158;

    /**
     * Electro.
     */
    const GENRE_ELECTRO = 159;

    /**
     * Electroclash.
     */
    const GENRE_ELECTROCLASH = 160;

    /**
     * Emo.
     */
    const GENRE_EMO = 161;

    /**
     * Experimental.
     */
    const GENRE_EXPERIMENTAL = 162;

    /**
     * Garage.
     */
    const GENRE_GARAGE = 163;

    /**
     * Global.
     */
    const GENRE_GLOBAL = 164;

    /**
     * IDM.
     */
    const GENRE_IDM = 165;

    /**
     * Illbient.
     */
    const GENRE_ILLBIENT = 166;

    /**
     * Industro-Goth.
     */
    const GENRE_INDUSTRO_GOTH = 167;

    /**
     * Jam Band.
     */
    const GENRE_JAM_BAND = 168;

    /**
     * Krautrock.
     */
    const GENRE_KRAUTROCK = 169;

    /**
     * Leftfield.
     */
    const GENRE_LEFTFIELD = 170;

    /**
     * Lounge.
     */
    const GENRE_LOUNGE = 171;

    /**
     * Math Rock.
     */
    const GENRE_MATH_ROCK = 172;

    /**
     * New Romantic.
     */
    const GENRE_NEW_ROMANTIC = 173;

    /**
     * Nu-Breakz.
     */
    const GENRE_NU_BREAKZ = 174;

    /**
     * Post-Punk.
     */
    const GENRE_POST_PUNK = 175;

    /**
     * Post-Rock.
     */
    const GENRE_POST_ROCK = 176;

    /**
     * Psytrance.
     */
    const GENRE_PSYTRANCE = 177;

    /**
     * Shoegaze.
     */
    const GENRE_SHOEGAZE = 178;

    /**
     * Space Rock.
     */
    const GENRE_SPACE_ROCK = 179;

    /**
     * Trop Rock.
     */
    const GENRE_TROP_ROCK = 180;

    /**
     * World Music.
     */
    const GENRE_WORLD_MUSIC = 181;

    /**
     * Neoclassical.
     */
    const GENRE_NEOCLASSICAL = 182;

    /**
     * Audiobook.
     */
    const GENRE_AUDIOBOOK = 183;

    /**
     * Audio Theatre.
     */
    const GENRE_AUDIO_THEATRE = 184;

    /**
     * Neue Deutsche Welle.
     */
    const GENRE_NEUE_DEUTSCHE_WELLE = 185;

    /**
     * Podcast.
     */
    const GENRE_PODCAST = 186;

    /**
     * Indie Rock.
     */
    const GENRE_INDIE_ROCK = 187;

    /**
     * G-Funk.
     */
    const GENRE_G_FUNK = 188;

    /**
     * Dubstep.
     */
    const GENRE_DUBSTEP = 189;

    /**
     * Garage Rock.
     */
    const GENRE_GARAGE_ROCK = 190;

    /**
     * Psybient.
     */
    const GENRE_PSYBIENT = 191;

    /**
     * Valid values.
     *
     * @var int[]
     */
    protected static $values = [
        self::GENRE_BLUES,
        self::GENRE_CLASSIC_ROCK,
        self::GENRE_COUNTRY,
        self::GENRE_DANCE,
        self::GENRE_DISCO,
        self::GENRE_FUNK,
        self::GENRE_GRUNGE,
        self::GENRE_HIP_HOP,
        self::GENRE_JAZZ,
        self::GENRE_METAL,
        self::GENRE_NEW_AGE,
        self::GENRE_OLDIES,
        self::GENRE_OTHER,
        self::GENRE_POP,
        self::GENRE_R_AND_B,
        self::GENRE_RAP,
        self::GENRE_REGGAE,
        self::GENRE_ROCK,
        self::GENRE_TECHNO,
        self::GENRE_INDUSTRIAL,
        self::GENRE_ALTERNATIVE,
        self::GENRE_SKA,
        self::GENRE_DEATH_METAL,
        self::GENRE_PRANKS,
        self::GENRE_SOUNDTRACK,
        self::GENRE_EURO_TECHNO,
        self::GENRE_AMBIENT,
        self::GENRE_TRIP_HOP,
        self::GENRE_VOCAL,
        self::GENRE_JAZZ_AND_FUNK,
        self::GENRE_FUSION,
        self::GENRE_TRANCE,
        self::GENRE_CLASSICAL,
        self::GENRE_INSTRUMENTAL,
        self::GENRE_ACID,
        self::GENRE_HOUSE,
        self::GENRE_GAME,
        self::GENRE_SOUND_CLIP,
        self::GENRE_GOSPEL,
        self::GENRE_NOISE,
        self::GENRE_ALTERNATIVE_ROCK,
        self::GENRE_BASS,
        self::GENRE_SOUL,
        self::GENRE_PUNK,
        self::GENRE_SPACE,
        self::GENRE_MEDITATIVE,
        self::GENRE_INSTRUMENTAL_POP,
        self::GENRE_INSTRUMENTAL_ROCK,
        self::GENRE_ETHNIC,
        self::GENRE_GOTHIC,
        self::GENRE_DARKWAVE,
        self::GENRE_TECHNO_INDUSTRIAL,
        self::GENRE_ELECTRONIC,
        self::GENRE_POP_FOLK,
        self::GENRE_EURODANCE,
        self::GENRE_DREAM,
        self::GENRE_SOUTHERN_ROCK,
        self::GENRE_COMEDY,
        self::GENRE_CULT,
        self::GENRE_GANGSTA_RAP,
        self::GENRE_TOP_40,
        self::GENRE_CHRISTIAN_RAP,
        self::GENRE_POP_OR_FUNK,
        self::GENRE_JUNGLE,
        self::GENRE_NATIVE_AMERICAN,
        self::GENRE_CABARET,
        self::GENRE_NEW_WAVE,
        self::GENRE_PSYCHEDELIC,
        self::GENRE_RAVE,
        self::GENRE_SHOWTUNES,
        self::GENRE_TRAILER,
        self::GENRE_LO_FI,
        self::GENRE_TRIBAL,
        self::GENRE_ACID_PUNK,
        self::GENRE_ACID_JAZZ,
        self::GENRE_POLKA,
        self::GENRE_RETRO,
        self::GENRE_MUSICAL,
        self::GENRE_ROCK_AND_ROLL,
        self::GENRE_HARD_ROCK,
        self::GENRE_FOLK,
        self::GENRE_FOLK_OR_ROCK,
        self::GENRE_NATIONAL_FOLK,
        self::GENRE_SWING,
        self::GENRE_FAST_FUSION,
        self::GENRE_BEBOB,
        self::GENRE_LATIN,
        self::GENRE_REVIVAL,
        self::GENRE_CELTIC,
        self::GENRE_BLUEGRASS,
        self::GENRE_AVANTGARDE,
        self::GENRE_GOTHIC_ROCK,
        self::GENRE_PROGRESSIVE_ROCK,
        self::GENRE_PSYCHEDELIC_ROCK,
        self::GENRE_SYMPHONIC_ROCK,
        self::GENRE_SLOW_ROCK,
        self::GENRE_BIG_BAND,
        self::GENRE_CHORUS,
        self::GENRE_EASY_LISTENING,
        self::GENRE_ACOUSTIC,
        self::GENRE_HUMOUR,
        self::GENRE_SPEECH,
        self::GENRE_CHANSON,
        self::GENRE_OPERA,
        self::GENRE_CHAMBER_MUSIC,
        self::GENRE_SONATA,
        self::GENRE_SYMPHONY,
        self::GENRE_BOOTY_BASS,
        self::GENRE_PRIMUS,
        self::GENRE_PORN_GROOVE,
        self::GENRE_SATIRE,
        self::GENRE_SLOW_JAM,
        self::GENRE_CLUB,
        self::GENRE_TANGO,
        self::GENRE_SAMBA,
        self::GENRE_FOLKLORE,
        self::GENRE_BALLAD,
        self::GENRE_POWER_BALLAD,
        self::GENRE_RHYTHMIC_SOUL,
        self::GENRE_FREESTYLE,
        self::GENRE_DUET,
        self::GENRE_PUNK_ROCK,
        self::GENRE_DRUM_SOLO,
        self::GENRE_A_CAPPELLA,
        self::GENRE_EURO_HOUSE,
        self::GENRE_DANCE_HALL,
        self::GENRE_GOA,
        self::GENRE_DRUM_AND_BASS,
        self::GENRE_CLUB_HOUSE,
        self::GENRE_HARDCORE,
        self::GENRE_TERROR,
        self::GENRE_INDIE,
        self::GENRE_BRITPOP,
        self::GENRE_NEGERPUNK,
        self::GENRE_POLSK_PUNK,
        self::GENRE_BEAT,
        self::GENRE_CHRISTIAN_GANGSTA_RAP,
        self::GENRE_HEAVY_METAL,
        self::GENRE_BLACK_METAL,
        self::GENRE_CROSSOVER,
        self::GENRE_CONTEMPORARY_CHRISTIAN,
        self::GENRE_CHRISTIAN_ROCK,
        self::GENRE_MERENGUE,
        self::GENRE_SALSA,
        self::GENRE_THRASH_METAL,
        self::GENRE_ANIME,
        self::GENRE_JPOP,
        self::GENRE_SYNTHPOP,
        self::GENRE_ABSTRACT,
        self::GENRE_ART_ROCK,
        self::GENRE_BAROQUE,
        self::GENRE_BHANGRA,
        self::GENRE_BIG_BEAT,
        self::GENRE_BREAKBEAT,
        self::GENRE_CHILLOUT,
        self::GENRE_DOWNTEMPO,
        self::GENRE_DUB,
        self::GENRE_EBM,
        self::GENRE_ECLECTIC,
        self::GENRE_ELECTRO,
        self::GENRE_ELECTROCLASH,
        self::GENRE_EMO,
        self::GENRE_EXPERIMENTAL,
        self::GENRE_GARAGE,
        self::GENRE_GLOBAL,
        self::GENRE_IDM,
        self::GENRE_ILLBIENT,
        self::GENRE_INDUSTRO_GOTH,
        self::GENRE_JAM_BAND,
        self::GENRE_KRAUTROCK,
        self::GENRE_LEFTFIELD,
        self::GENRE_LOUNGE,
        self::GENRE_MATH_ROCK,
        self::GENRE_NEW_ROMANTIC,
        self::GENRE_NU_BREAKZ,
        self::GENRE_POST_PUNK,
        self::GENRE_POST_ROCK,
        self::GENRE_PSYTRANCE,
        self::GENRE_SHOEGAZE,
        self::GENRE_SPACE_ROCK,
        self::GENRE_TROP_ROCK,
        self::GENRE_WORLD_MUSIC,
        self::GENRE_NEOCLASSICAL,
        self::GENRE_AUDIOBOOK,
        self::GENRE_AUDIO_THEATRE,
        self::GENRE_NEUE_DEUTSCHE_WELLE,
        self::GENRE_PODCAST,
        self::GENRE_INDIE_ROCK,
        self::GENRE_G_FUNK,
        self::GENRE_DUBSTEP,
        self::GENRE_GARAGE_ROCK,
        self::GENRE_PSYBIENT,
    ];

    /**
     * Return valid values.
     *
     * @return int[]
     */
    public static function values()
    {
        return static::$values;
    }
}
