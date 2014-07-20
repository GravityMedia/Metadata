<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\GetId3;

use getid3_lib as GetId3Lib;
use GravityMedia\Metadata\GetId3;

/**
 * Factory object
 *
 * @package GravityMedia\Metadata\GetId3
 */
class Factory
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var resource
     */
    protected $fp;

    /**
     * @var array
     */
    protected $info;

    /**
     * Constructor
     */
    function __construct()
    {
        $getId3 = GetId3::getInstance()->getGetId3();
        $this->filename = & $getId3->filename;
        $this->fp = & $getId3->fp;
        $this->info = & $getId3->info;
    }

    /**
     * Create reader
     *
     * @param string $group
     * @param string $module
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\GetId3\Reader
     */
    protected function createReader($group, $module, $name)
    {
        $className = GetId3::includeModule($group, $module);
        $getId3 = GetId3::getInstance()->getGetId3();
        return new Reader(new $className($getId3), $name);
    }

    /**
     * Create writer
     *
     * @param string $name
     *
     * @return \GravityMedia\Metadata\GetId3\Writer
     */
    protected function createWriter($name)
    {
        $className = GetId3::includeWriter();
        return new Writer(new $className(), $name);
    }

    /**
     * Create audio format reader
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\GetId3\Reader
     */
    public function createAudioFormatReader()
    {
        // detect data offset
        $offset = $this->info['avdataoffset'];
        if (isset($this->info['id3v2']['tag_offset_start'])) {
            $offset = max($offset, $this->info['id3v2']['tag_offset_end']);
        }

        // length of ID3 v2 tag in 10-byte header doesn't include 10-byte header length
        if (!isset($this->info['id3v2'])) {
            fseek($this->fp, 0);
            $header = fread($this->fp, 10);
            if ('ID3' === substr($header, 0, 3) && 10 == strlen($header)) {
                $offset += GetId3Lib::BigEndian2Int(substr($header, 6, 4), 1) + 10;
            }
        }

        // read 32 kb file data
        fseek($this->fp, $offset, SEEK_SET);
        $data = fread($this->fp, 32774);

        // detect format
        $format = GetId3::getInstance()->getGetId3()->GetFileFormat($data, $this->filename);

        // unable to detect format
        if (!$format) {
            GetId3::getInstance()->close();
            throw new \RuntimeException(sprintf('Unable to determine file format of "%s".', $this->filename));
        }

        // check for illegal ID3 tags
        if ((isset($this->info['tags']['id3v1']) || isset($this->info['tags']['id3v2']))
            && isset($format['fail_id3'])
            && 'ERROR' === $format['fail_id3']
        ) {
            GetId3::getInstance()->close();
            throw new \RuntimeException('ID3 tags not allowed on this file type.');
        }

        // check for illegal APE tags
        if (isset($this->info['tags']['ape'])
            && isset($format['fail_ape'])
            && 'ERROR' === $format['fail_ape']
        ) {
            GetId3::getInstance()->close();
            throw new \RuntimeException('APE tags not allowed on this file type.');
        }

        return $this->createReader($format['group'], $format['module'], 'audio');
    }

    /**
     * Create ID3 v1 tag reader
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\GetId3\Reader
     */
    public function createId3v1TagReader()
    {
        return $this->createReader('tag', 'id3v1', 'id3v1');
    }

    /**
     * Create ID3 v2 tag reader
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\GetId3\Reader
     */
    public function createId3v2TagReader()
    {
        return $this->createReader('tag', 'id3v2', 'id3v2');
    }

    /**
     * Create ID3 v1 tag writer
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\GetId3\Writer
     */
    public function createId3v1TagWriter()
    {
        return $this->createWriter('id3v1');
    }

    /**
     * Create ID3 v2 tag writer
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\GetId3\Writer
     */
    public function createId3v2TagWriter()
    {
        return $this->createWriter('id3v2.3');
    }
}
