<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Console\Command;

use GravityMedia\Metadata\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Import ID3 v1 command object
 *
 * @package GravityMedia\Metadata\Console\Command
 */
class ImportId3v1Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('import:id3v1')
            ->setDescription('Import ID3 v1 metadata')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'The name of the file'
            )
            ->addArgument(
                'import',
                InputArgument::REQUIRED,
                'The name of the import file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new SplFileInfo($input->getArgument('filename'));
        $data = Yaml::parse($input->getArgument('import'));
        foreach (array_keys($data) as $name) {
            if (null === $data[$name] || 'audio_properties' === $name) {
                unset($data[$name]);
            }
        }

        $metadata = $file->getMetadata();
        $tag = $metadata->getId3v1Tag();

        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $tag)->save();

        $output->writeln('<info>Metadata import successful.</info>');
    }
}
