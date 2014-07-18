<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Tag;

use GetId3\GetId3Core;
use GetId3\Write\Tags as TagWriter;
use GravityMedia\MediaTags\Meta\AudioProperties as AudioProperties;

/**
 * Abstract tag
 *
 * @package GravityMedia\MediaTags\Tag
 */
abstract class AbstractTag
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var \GetId3\GetId3Core
     */
    protected $getid3;

    /**
     * @var \GravityMedia\MediaTags\Meta\AudioProperties
     */
    protected $audioProperties;

    /**
     * Constructor
     *
     * @param \SplFileInfo $file
     * @param \GetId3\GetId3Core $getid3
     */
    function __construct(\SplFileInfo $file, GetId3Core $getid3)
    {
        $this->file = $file;
        $this->getid3 = $getid3;
    }

    /**
     * Write tag
     *
     * @param string $format
     * @param array $data
     *
     * @return $this
     */
    protected function write($format, array $data = null)
    {
        $filename = $this->file->getRealPath();

        $tagWriter = new TagWriter();
        $tagWriter->filename = $filename;
        if (null === $data) {
            if (!$tagWriter->DeleteTags(array($format))) {
                /** @var resource $fp */
                $fp = $this->getid3->getFp();
                fclose($fp);

                $error = implode(PHP_EOL, $tagWriter->errors);
                throw new \RuntimeException(sprintf('Error while deleting tags in "%s": %s.', $filename, $error));
            }
        } else {
            $tagWriter->tagformats = array($format);
            $tagWriter->tag_data = $data;

            if (!$tagWriter->WriteTags()) {
                /** @var resource $fp */
                $fp = $this->getid3->getFp();
                fclose($fp);

                $error = implode(PHP_EOL, $tagWriter->errors);
                throw new \RuntimeException(sprintf('Error while writing tags to "%s": %s.', $filename, $error));
            }
        }

        return $this;
    }

    /**
     * Set audio properties
     *
     * @param \GravityMedia\MediaTags\Meta\AudioProperties $audioProperties
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
     * @return \GravityMedia\MediaTags\Meta\AudioProperties
     */
    public function getAudioProperties()
    {
        return $this->audioProperties;
    }
}
