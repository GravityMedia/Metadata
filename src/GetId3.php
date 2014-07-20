<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata;

use GravityMedia\Metadata\GetId3\Factory;

/**
 * GetId3 singleton object
 *
 * @package GravityMedia\Metadata
 */
class GetId3
{
    /**
     * @var \GravityMedia\Metadata\GetId3
     */
    protected static $INSTANCE;

    /**
     * @var \getID3
     */
    protected $getId3;

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
    protected function __construct()
    {
        $this->getId3 = new \getID3();
        $this->filename = & $this->getId3->filename;
        $this->fp = & $this->getId3->fp;
        $this->info = & $this->getId3->info;
    }

    /**
     * Get GetId3
     *
     * @return \getID3
     */
    public function getGetId3()
    {
        return $this->getId3;
    }

    /**
     * Open file
     *
     * @param \SplFileInfo $file
     *
     * @return \GravityMedia\Metadata\GetId3\Factory
     */
    public function open(\SplFileInfo $file)
    {
        if (!$this->getId3->openfile($file->getRealPath())) {
            $this->close();
            throw new \RuntimeException(sprintf('Error while opening file "%s": %s.', $this->filename, implode(PHP_EOL, $this->info['error'])));
        }
        return new Factory($this);
    }

    /**
     * Close file
     *
     * @return $this
     */
    public function close()
    {
        if (is_resource($this->fp)) {
            fclose($this->fp);
        }
        return $this;
    }

    /**
     * Include module
     *
     * @param string $group
     * @param string $module
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function includeModule($group, $module)
    {
        $className = sprintf('getid3_%s', $module);
        if (!class_exists($className)) {
            try {
                static::getInstance()->getGetId3()->include_module(sprintf('%s.%s', $group, $module));
            } catch (\Exception $exception) {
                throw new \RuntimeException(sprintf('Error while including module: Required module "%s" not found in group "%s".', $module, $group));
            }
        }
        return $className;
    }

    /**
     * Include writer
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function includeWriter()
    {
        $className = 'getid3_writetags';
        if (!class_exists($className)) {
            // get instance to make sure GETID3 constants are defined
            static::getInstance();
            $filename = GETID3_INCLUDEPATH . 'write.php';
            if (!file_exists($filename)) {
                throw new \RuntimeException('Error while including writer: Writer module not found.');
            }
            require_once $filename;
        }
        return $className;
    }

    /**
     * Get instance
     *
     * @return \GravityMedia\Metadata\GetId3
     */
    public static function getInstance()
    {
        if (null === static::$INSTANCE) {
            static::$INSTANCE = new static();
        }
        return static::$INSTANCE;
    }

    /**
     * Get list of ID3 v1 genres
     *
     * @return array
     */
    public static function getId3v1Genres()
    {
        return call_user_func(array(static::includeModule('tag', 'id3v1'), 'ArrayOfGenres'));
    }

    /**
     * Get list of ID3 v2 picture types
     *
     * @return array
     */
    public static function getId3v2PictureTypes()
    {
        return call_user_func(array(static::includeModule('tag', 'id3v2'), 'APICPictureTypeLookup'), null, true);
    }

    /**
     * Lookup ID3 v2 frame name
     *
     * @param string $frameName
     *
     * @return string
     */
    public static function lookupId3v2FrameName($frameName)
    {
        return call_user_func(array(static::includeModule('tag', 'id3v2'), 'FrameNameShortLookup'), $frameName);
    }
}
