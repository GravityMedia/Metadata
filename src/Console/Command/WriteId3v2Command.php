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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Write ID3 V2 command
 *
 * @package GravityMedia\MediaTags\Console\Command
 */
class WriteId3v2Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('write:id3v2')
            ->setDescription('Write ID3 V2 media tags')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'The name of the file'
            )
            ->addArgument(
                'tags',
                InputArgument::REQUIRED,
                'The name of the file with the tags'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new SplFileInfo($input->getArgument('filename'));
        $data = Yaml::parse($input->getArgument('tags'));

        $mediaTags = $file->getMediaTags();
        $tag = $mediaTags->getId3v2();

        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $tag)->save();

        $output->writeln('<info>' . Yaml::dump($data) . '</info>');
    }
}
