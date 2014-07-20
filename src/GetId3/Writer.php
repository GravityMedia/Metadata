<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\GetId3;

use getid3_writetags as TagWriter;
use GravityMedia\Metadata\GetId3;

/**
 * Writer object
 *
 * @package GravityMedia\Metadata\GetId3
 */
class Writer
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
     * @var TagWriter
     */
    protected $tagWriter;

    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param TagWriter $tagWriter
     * @param string $name
     */
    function __construct(TagWriter $tagWriter, $name)
    {
        $getId3 = GetId3::getInstance()->getGetId3();
        $this->filename = & $getId3->filename;
        $this->fp = & $getId3->fp;
        $this->info = & $getId3->info;
        $this->tagWriter = $tagWriter;
        $this->name = $name;
    }

    /**
     * Write metadata
     *
     * @param array $data
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function write(array $data = null)
    {
        $this->tagWriter->filename = $this->filename;

        if (null === $data) {
            if (!$this->tagWriter->DeleteTags(array($this->name))) {
                GetId3::getInstance()->close();
                throw new \RuntimeException(sprintf('Error while deleting metadata from "%s": %s.', $this->tagWriter->filename, implode(PHP_EOL, $this->tagWriter->errors)));
            }
        } else {
            $this->tagWriter->tagformats = array($this->name);
            $this->tagWriter->tag_data = $data;
            if (!$this->tagWriter->WriteTags()) {
                GetId3::getInstance()->close();
                throw new \RuntimeException(sprintf('Error while writing metadata to "%s": %s.', $this->tagWriter->filename, implode(PHP_EOL, $this->tagWriter->errors)));
            }
        }

        return $this;
    }
}
