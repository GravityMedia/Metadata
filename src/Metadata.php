<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata;

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
     * Constructor
     *
     * @param \SplFileInfo $file
     */
    function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * Get ID3 V1 tag
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\Tag\Id3v1
     */
    public function getId3v1Tag()
    {
        $factory = GetId3::getInstance()->open($this->file);
        $properties = $factory->createId3v1TagReader()->read();
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

        // add audio properties
        $data['audio_properties'] = $hydrator->hydrate(
            $factory->createAudioFormatReader()->read(),
            new AudioProperties()
        );

        return $hydrator->hydrate($data, new Id3v1Tag($factory->createId3v1TagWriter()));
    }

    /**
     * Get ID3 V2 tag
     *
     * @throws \RuntimeException
     *
     * @return \GravityMedia\Metadata\Tag\Id3v2
     */
    public function getId3v2Tag()
    {
        $factory = GetId3::getInstance()->open($this->file);
        $properties = $factory->createId3v2TagReader()->read();
        $hydrator = new ClassMethods();
        $data = array();

        unset($properties['comments']);
        foreach ($properties as $frameName => $values) {
            if (!in_array($frameName, Id3v2Tag::$FRAMENAMES)) {
                continue;
            }
            /** @var string $name */
            $name = GetId3::lookupId3v2FrameName($frameName);
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

        // add audio properties
        $data['audio_properties'] = $hydrator->hydrate(
            $factory->createAudioFormatReader()->read(),
            new AudioProperties()
        );

        return $hydrator->hydrate($data, new Id3v2Tag($factory->createId3v2TagWriter()));
    }
}
