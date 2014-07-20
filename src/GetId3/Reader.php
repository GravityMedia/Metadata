<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\GetId3;

use getid3_handler as AbstractHandler;
use GravityMedia\Metadata\GetId3;

/**
 * Reader object
 *
 * @package GravityMedia\Metadata\GetId3
 */
class Reader
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
     * @var AbstractHandler
     */
    protected $handler;

    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param AbstractHandler $handler
     * @param string $name
     */
    function __construct(AbstractHandler $handler, $name)
    {
        $getId3 = GetId3::getInstance()->getGetId3();
        $this->filename = & $getId3->filename;
        $this->fp = & $getId3->fp;
        $this->info = & $getId3->info;
        $this->handler = $handler;
        $this->name = $name;
    }

    /**
     * Read metadata
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function read()
    {
        $this->handler->Analyze();
        if (!empty($this->info['error'])) {
            GetId3::getInstance()->close();
            throw new \RuntimeException(sprintf('Error while reading metadata from "%s": %s.', $this->filename, implode(PHP_EOL, $this->info['error'])));
        }

        if (isset($this->info[$this->name])) {
            $data = $this->info[$this->name];
            if (in_array($this->name, array('audio', 'video'))) {
                $data['playtime'] = $this->info['playtime_seconds'];
            }
            return $data;
        }

        return array();
    }
}
