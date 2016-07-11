<?php
/**
 * This file is part of the ID3 project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\ID3v2;

/**
 * ID3v2 picture type class.
 *
 * @package GravityMedia\Metadata\ID3v2
 */
class PictureType
{
    /**
     * Other.
     */
    const OTHER = 0;

    /**
     * 32x32 pixels 'file icon' (PNG only).
     */
    const FILE_ICON_PNG = 1;

    /**
     * Other file icon.
     */
    const FILE_ICON_OTHER = 2;

    /**
     * Cover (front).
     */
    const COVER_FRONT = 3;

    /**
     * Cover (back).
     */
    const COVER_BACK = 4;

    /**
     * Leaflet page.
     */
    const LEAFLET_PAGE = 5;

    /**
     * Media (e.g. label side of CD).
     */
    const MEDIA = 6;

    /**
     * Lead artist/lead performer/soloist.
     */
    const LEAD_ARTIST_OR_LEAD_PERFORMER_OR_SOLOIST = 7;

    /**
     * Artist/performer.
     */
    const ARTIST_OR_PERFORMER = 8;

    /**
     * Conductor.
     */
    const CONDUCTOR = 9;

    /**
     * Band/Orchestra.
     */
    const BAND_OR_ORCHESTRA = 9;

    /**
     * Composer.
     */
    const COMPOSER = 10;

    /**
     * Lyricist/text writer.
     */
    const LYRICIST_OR_TEXT_WRITER = 11;

    /**
     * Recording location.
     */
    const RECORDING_LOCATION = 12;

    /**
     * During recording.
     */
    const DURING_RECORDING = 13;

    /**
     * During performance.
     */
    const DURING_PERFORMANCE = 14;

    /**
     * Movie/video screen capture.
     */
    const MOVIE_OR_VIDEO_SCREEN_CAPTURE = 15;

    /**
     * A bright coloured fish.
     */
    const A_BRIGHT_COLOURED_FISH = 16;

    /**
     * Illustration.
     */
    const ILLUSTRATION = 17;

    /**
     * Band/artist logotype.
     */
    const BAND_OR_ARTIST_LOGOTYPE = 18;

    /**
     * Publisher/Studio logotype.
     */
    const PUBLISHER_OR_STUDIO_LOGOTYPE = 19;

    /**
     * Valid values.
     *
     * @var int[]
     */
    protected static $values = [
        self::OTHER,
        self::FILE_ICON_PNG,
        self::FILE_ICON_OTHER,
        self::COVER_FRONT,
        self::COVER_BACK,
        self::LEAFLET_PAGE,
        self::MEDIA,
        self::LEAD_ARTIST_OR_LEAD_PERFORMER_OR_SOLOIST,
        self::ARTIST_OR_PERFORMER,
        self::CONDUCTOR,
        self::BAND_OR_ORCHESTRA,
        self::COMPOSER,
        self::LYRICIST_OR_TEXT_WRITER,
        self::RECORDING_LOCATION,
        self::DURING_RECORDING,
        self::DURING_PERFORMANCE,
        self::MOVIE_OR_VIDEO_SCREEN_CAPTURE,
        self::A_BRIGHT_COLOURED_FISH,
        self::ILLUSTRATION,
        self::BAND_OR_ARTIST_LOGOTYPE,
        self::PUBLISHER_OR_STUDIO_LOGOTYPE
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
