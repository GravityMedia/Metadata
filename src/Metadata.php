<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata;

use GetId3\GetId3Core;
use GetId3\Handler\BaseHandler as Processor;
use GetId3\Module\Tag\Id3v1 as Id3v1Processor;
use GetId3\Module\Tag\Id3v2 as Id3v2Processor;
use GravityMedia\Metadata\Feature\AudioProperties;
use GravityMedia\Metadata\Feature\Picture;
use GravityMedia\Metadata\Tag\Id3v1 as Id3v1Tag;
use GravityMedia\Metadata\Tag\Id3v2 as Id3v2Tag;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Metadata object
 *
 * @package GravityMedia\Metadata
 */
class Metadata
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
     * Constructor
     *
     * @throws \RuntimeException
     *
     * @param \SplFileInfo $file
     */
    function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
        try {
            $this->getid3 = new GetId3Core();
        } catch (\Exception $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }

    /**
     * Get processor for detected audio file format
     *
     * @throws \RuntimeException
     *
     * @return \GetId3\Handler\BaseHandler
     */
    protected function getFormatProcessor()
    {
        /** @var resource $fp */
        $fp = $this->getid3->getFp();
        $info = $this->getid3->getInfo();
        $offset = $info['avdataoffset'];
        $filename = $this->file->getRealPath();

        // read 32 kb file data
        fseek($fp, $offset, SEEK_SET);
        $data = fread($fp, 32774);

        // detect format
        $format = $this->getid3->GetFileFormat($data, $filename);

        // unable to detect format
        if (!$format) {
            fclose($fp);
            throw new \RuntimeException(sprintf('Unable to determine file format of "%s".', $filename));
        }

        // check for illegal ID3 tags
        if (isset($format['fail_id3'])
            && (in_array('id3v1', $info['tags']) || in_array('id3v2', $info['tags']))
            && 'ERROR' === $format['fail_id3']
        ) {
            fclose($fp);
            throw new \RuntimeException('ID3 tags not allowed on this file type.');
        }

        // check for illegal APE tags
        if (isset($format['fail_ape'])
            && in_array('ape', $info['tags'])
            && 'ERROR' === $format['fail_ape']
        ) {
            fclose($fp);
            throw new \RuntimeException('APE tags not allowed on this file type.');
        }

        return new $format['class']($this->getid3);
    }

    /**
     * Open media file
     *
     * @throws \RuntimeException
     *
     * @return \GetId3\GetId3Core
     */
    protected function open()
    {
        $filename = $this->file->getRealPath();
        if (!$this->getid3->openfile($filename)) {
            /** @var resource $fp */
            $fp = $this->getid3->getFp();
            fclose($fp);

            $info = $this->getid3->getInfo();
            $error = implode(PHP_EOL, $info['error']);
            throw new \RuntimeException(sprintf('Error while reading tags from "%s": %s.', $filename, $error));
        }
        return $this->getid3;
    }

    /**
     * Process media file
     *
     * @param \GetId3\Handler\BaseHandler $processor
     * @param string $tagName
     *
     * @return array
     */
    protected function process(Processor $processor, $tagName)
    {
        $processor->analyze();
        $info = $this->getid3->getInfo();
        //$warnings = $info['warning'];
        if (isset($info[$tagName])) {
            return $info[$tagName];
        }
        return array();
    }

    /**
     * Get ID3 V1 tag
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\Tag\Id3v1
     */
    public function getId3v1()
    {
        $processor = new Id3v1Processor($this->open());
        $properties = $this->process($processor, 'id3v1');
        $hydrator = new ClassMethods();
        $data = array();

        unset($properties['comments']);
        foreach ($properties as $name => $value) {
            $value = iconv('ISO-8859-1', 'UTF-8', $value);
            switch ($name) {
                case 'year':
                    $data['year'] = intval($value);
                    break;
                default:
                    $data[$name] = $value;
                    break;
            }
        }

        /** @var \GravityMedia\Metadata\Tag\Id3v1 $tag */
        $tag = $hydrator->hydrate(
            $data,
            new Id3v1Tag($this->file, $this->getid3)
        );

        /** @var \GravityMedia\Metadata\Feature\AudioProperties $audioProperties */
        $audioProperties = $hydrator->hydrate(
            $this->process($this->getFormatProcessor(), 'audio'),
            new AudioProperties()
        );

        $info = $this->getid3->getInfo();
        $audioProperties->setPlaytime($info['playtime_seconds']);

        return $tag->setAudioProperties($audioProperties);
    }

    /**
     * Get ID3 V2 tag
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\Tag\Id3v2
     */
    public function getId3v2()
    {
        $processor = new Id3v2Processor($this->open());
        $properties = $this->process($processor, 'id3v2');
        $hydrator = new ClassMethods();
        $data = array();

        unset($properties['comments']);
        foreach ($properties as $framename => $values) {
            if (!in_array($framename, Id3v2Tag::$FRAMENAMES)) {
                continue;
            }
            /** @var string $name */
            $name = $processor->FrameNameShortLookup($framename);
            switch ($name) {
                case 'attached_picture':
                    $data['picture'] = $hydrator->hydrate($values[0], new Picture());
                    break;
                default:
                    $value = iconv($values[0]['encoding'], 'UTF-8', $values[0]['data']);
                    // map name and/or value
                    switch ($name) {
                        case 'year':
                            $data['year'] = intval($value);
                            break;
                        case 'track_number':
                            $disc = explode('/', $value, 2);
                            $data['track'] = intval(array_shift($disc));
                            if (count($disc) > 0) {
                                $data['track_count'] = intval(array_shift($disc));
                            }
                            break;
                        case 'content_group_description':
                            $data['works'] = $value;
                            break;
                        case 'part_of_a_set':
                            $disc = explode('/', $value, 2);
                            $data['disc'] = intval(array_shift($disc));
                            if (count($disc) > 0) {
                                $data['disc_count'] = intval(array_shift($disc));
                            }
                            break;
                        default:
                            $data[$name] = $value;
                            break;
                    }
                    break;
            }
        }

        /** @var \GravityMedia\Metadata\Tag\Id3v2 $tag */
        $tag = $hydrator->hydrate(
            $data,
            new Id3v2Tag($this->file, $this->getid3)
        );

        /** @var \GravityMedia\Metadata\Feature\AudioProperties $audioProperties */
        $audioProperties = $hydrator->hydrate(
            $this->process($this->getFormatProcessor(), 'audio'),
            new AudioProperties()
        );

        $info = $this->getid3->getInfo();
        $audioProperties->setPlaytime($info['playtime_seconds']);

        return $tag->setAudioProperties($audioProperties);
    }
}
