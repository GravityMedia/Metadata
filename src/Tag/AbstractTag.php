<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Tag;

use GravityMedia\Metadata\Feature\AudioProperties;
use GravityMedia\Metadata\GetId3\Writer;

/**
 * Abstract tag object
 *
 * @package GravityMedia\Metadata\Tag
 */
abstract class AbstractTag
{
    /**
     * @var \GravityMedia\Metadata\GetId3\Writer
     */
    protected $writer;

    /**
     * @var \GravityMedia\Metadata\Feature\AudioProperties
     */
    protected $audioProperties;

    /**
     * Constructor
     *
     * @param \GravityMedia\Metadata\GetId3\Writer $writer
     */
    function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Set audio properties
     *
     * @param \GravityMedia\Metadata\Feature\AudioProperties $audioProperties
     *
     * @return $this
     */
    public function setAudioProperties(AudioProperties $audioProperties)
    {
        $this->audioProperties = $audioProperties;
        return $this;
    }

    /**
     * Get audio properties
     *
     * @return \GravityMedia\Metadata\Feature\AudioProperties
     */
    public function getAudioProperties()
    {
        return $this->audioProperties;
    }
}
