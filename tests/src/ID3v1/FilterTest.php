<?php
/**
 * This file is part of the Metadata project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MetadataTest\ID3v1;

use GravityMedia\Metadata\ID3v1\Filter;

/**
 * ID3v1 filter test class.
 *
 * @package GravityMedia\MetadataTest\ID3v1
 *
 * @covers  GravityMedia\Metadata\ID3v1\Filter
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provide values to encode
     *
     * @return array
     */
    public function provideValuesToEncode()
    {
        return [
            ['phpunit', 3, 'php'],
            ['phpunit', 10, 'phpunit' . "\x00\x00\x00"]
        ];
    }

    /**
     * Provide values to decode
     *
     * @return array
     */
    public function provideValuesToDecode()
    {
        return [
            ['phpunit', 'phpunit'],
            ['phpunit' . "\x00\x00\x00", 'phpunit'],
            ["\x00\x00\x00" . 'phpunit', "\x00\x00\x00" . 'phpunit']
        ];
    }

    /**
     * Test that the data is being encoded correctly
     *
     * @dataProvider provideValuesToEncode()
     *
     * @param string $data
     * @param int    $length
     * @param string $value
     */
    public function testEncodeData($data, $length, $value)
    {
        $filter = new Filter();

        $this->assertSame($value, $filter->encode($data, $length));
    }

    /**
     * Test that the data is being decoded correctly
     *
     * @dataProvider provideValuesToDecode()
     *
     * @param string $data
     * @param string $value
     */
    public function testDecodeValue($data, $value)
    {
        $filter = new Filter();

        $this->assertSame($value, $filter->decode($data));
    }
}
