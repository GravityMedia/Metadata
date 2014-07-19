<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Feature;

use GetId3\Module\Tag\Id3v2 as Id3v2Processor;

/**
 * Picture object
 *
 * @package GravityMedia\Metadata\Meta
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
     * @var int
     */
    protected $pictureTypeId;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int[]
     */
    private $availablePictureTypes;

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
     * Set picture type ID
     *
     * @param int $pictureTypeId
     *
     * @throws \BadMethodCallException
     * @return $this
     */
    public function setPictureTypeId($pictureTypeId)
    {
        if (!is_array($this->availablePictureTypes)) {
            $this->availablePictureTypes = array_keys(Id3v2Processor::APICPictureTypeLookup(null, true));
        }
        if (in_array($pictureTypeId, $this->availablePictureTypes)) {
            $this->pictureTypeId = $pictureTypeId;
            return $this;
        }
        throw new \BadMethodCallException(sprintf('The picture type ID "%s" is not available', $pictureTypeId));
    }

    /**
     * Get picture type ID
     *
     * @return int
     */
    public function getPictureTypeId()
    {
        return $this->pictureTypeId;
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
