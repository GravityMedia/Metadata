<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Feature;

use GravityMedia\Metadata\GetId3;

/**
 * Picture object
 *
 * @package GravityMedia\Metadata\Feature
 */
class Picture
{
    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $mime;

    /**
     * @var string
     */
    protected $pictureType;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int[]
     */
    protected $availablePictureTypes;

    /**
     * Set data
     *
     * @param string $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set mime type
     *
     * @param string $mime
     *
     * @return $this
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
        return $this;
    }

    /**
     * Get mime type
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set picture type
     *
     * @param string $pictureType
     *
     * @throws \BadMethodCallException
     *
     * @return $this
     */
    public function setPictureType($pictureType)
    {
        if (!is_array($this->availablePictureTypes)) {
            $this->availablePictureTypes = array_values(GetId3::getId3v2PictureTypes());
        }
        if (in_array($pictureType, $this->availablePictureTypes)) {
            $this->pictureType = $pictureType;
            return $this;
        }
        throw new \BadMethodCallException(sprintf('The picture type "%s" is not available', $pictureType));
    }

    /**
     * Get picture type
     *
     * @return string
     */
    public function getPictureType()
    {
        return $this->pictureType;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
