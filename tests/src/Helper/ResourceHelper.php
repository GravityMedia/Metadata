<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\Helper;

/**
 * Resource helper class
 *
 * @package GravityMedia\MetadataTest\Helper
 */
class ResourceHelper
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Create resource helper object
     *
     * @param string $source
     */
    public function __construct($source = null)
    {
        $resource = fopen('php://temp', 'r+');
        if (null !== $source) {
            fwrite($resource, file_get_contents($source));
            fseek($resource, 0);
        }

        $this->resource = $resource;
    }

    /**
     * Destroy resource helper object
     */
    public function __destruct()
    {
        @fclose($this->resource);
    }

    /**
     * Get resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
