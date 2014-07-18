<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Console\Command;

use GravityMedia\MediaTags\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Read ID3 V2 command
 *
 * @package GravityMedia\MediaTags\Console\Command
 */
class ReadId3v2Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('read:id3v2')
            ->setDescription('Read ID3 V2 media tag from file')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'The name of the file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new SplFileInfo($input->getArgument('filename'));

        $mediaTags = $file->getMediaTags();
        $tag = $mediaTags->getId3v2();

        $hydrator = new ClassMethods();
        $data = $hydrator->extract($tag);
        if (isset($data['picture'])) {
            /** @var \GravityMedia\MediaTags\Meta\Picture $picture */
            $picture = $data['picture'];
            $data['picture'] = base64_encode($picture->getData());
        }
        if (isset($data['url'])) {
            /** @var \Zend\Uri\Uri $url */
            $url = $data['url'];
            $data['url'] = $url->toString();
        }
        $data['audio_properties'] = $hydrator->extract($data['audio_properties']);

        $output->writeln('<info>' . Yaml::dump($data) . '</info>');
    }
}
