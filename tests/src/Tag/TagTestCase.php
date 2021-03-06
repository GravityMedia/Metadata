<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\Tag;

/**
 * Tag test case
 *
 * @package GravityMedia\MetadataTest\Tag
 */
class TagTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Generate random string
     *
     * @param int $length
     *
     * @return string
     */
    protected function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $max)];
        }
        return $randomString;
    }
}
