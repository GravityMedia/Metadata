<?php
/**
 * This file is part of the Metadata package
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
        $target = tempnam(sys_get_temp_dir(), 'php');
        if (null !== $source) {
            copy($source, $target);
        }

        $this->resource = fopen($target, 'r+');
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
