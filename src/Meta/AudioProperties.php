<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Meta;

/**
 * Audio properties
 *
 * @package GravityMedia\MediaTags\Meta
 */
class AudioProperties
{
    const BITRATE_MODE_CBR = 'cbr';
    const BITRATE_MODE_VBR = 'vbr';

    const CHANNEL_MODE_STEREO = 'stereo';
    const CHANNEL_MODE_JOINT_STEREO = 'joint stereo';
    const CHANNEL_MODE_MONO = 'mono';

    /**
     * @var float
     */
    protected $playtime;

    /**
     * @var int
     */
    protected $channels;

    /**
     * @var int
     */
    protected $sampleRate;

    /**
     * @var float
     */
    protected $bitrate;

    /**
     * @var string
     */
    protected $channelMode;

    /**
     * @var string
     */
    protected $bitrateMode;

    /**
     * @var string
     */
    protected $codec;

    /**
     * @var string
     */
    protected $encoder;

    /**
     * @var boolean
     */
    protected $lossless;

    /**
     * @var string
     */
    protected $encoderOptions;

    /**
     * Set playtime in seconds
     *
     * @param float $playtime
     *
     * @return $this
     */
    public function setPlaytime($playtime)
    {
        $this->playtime = $playtime;
        return $this;
    }

    /**
     * Get playtime in seconds
     *
     * @return float
     */
    public function getPlaytime()
    {
        return $this->playtime;
    }

    /**
     * Set number of channels
     *
     * @param int $channels
     *
     * @return $this
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;
        return $this;
    }

    /**
     * Get number of channels
     *
     * @return int
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * Set sample rate
     *
     * @param int $sampleRate
     *
     * @return $this
     */
    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;
        return $this;
    }

    /**
     * Get sample rate
     *
     * @return int
     */
    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    /**
     * Set bitrate
     *
     * @param float $bitrate
     *
     * @return $this
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
        return $this;
    }

    /**
     * Get bitrate
     *
     * @return float
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * Set channel mode
     *
     * @param string $channelMode
     *
     * @return $this
     */
    public function setChannelMode($channelMode)
    {
        $this->channelMode = $channelMode;
        return $this;
    }

    /**
     * Get channel mode
     *
     * @return string
     */
    public function getChannelMode()
    {
        return $this->channelMode;
    }

    /**
     * Set bitrate mode
     *
     * @param string $bitrateMode
     *
     * @return $this
     */
    public function setBitrateMode($bitrateMode)
    {
        $this->bitrateMode = $bitrateMode;
        return $this;
    }

    /**
     * Get bitrate mode
     *
     * @return string
     */
    public function getBitrateMode()
    {
        return $this->bitrateMode;
    }

    /**
     * Set codec
     *
     * @param string $codec
     *
     * @return $this
     */
    public function setCodec($codec)
    {
        $this->codec = $codec;
        return $this;
    }

    /**
     * Get codec
     *
     * @return string
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * Set encoder
     *
     * @param string $encoder
     *
     * @return $this
     */
    public function setEncoder($encoder)
    {
        $this->encoder = $encoder;
        return $this;
    }

    /**
     * Get encoder
     *
     * @return string
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Set lossless
     *
     * @param boolean $lossless
     *
     * @return $this
     */
    public function setLossless($lossless)
    {
        $this->lossless = $lossless;
        return $this;
    }

    /**
     * Is lossless
     *
     * @return boolean
     */
    public function isLossless()
    {
        return $this->lossless;
    }

    /**
     * Set encoder options
     *
     * @param string $encoderOptions
     *
     * @return $this
     */
    public function setEncoderOptions($encoderOptions)
    {
        $this->encoderOptions = $encoderOptions;
        return $this;
    }

    /**
     * Get encoder options
     *
     * @return string
     */
    public function getEncoderOptions()
    {
        return $this->encoderOptions;
    }
}
