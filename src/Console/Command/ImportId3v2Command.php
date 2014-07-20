<?php
/**
 * This file is part of the metadata package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Metadata\Console\Command;

use GravityMedia\Metadata\Feature\Picture;
use GravityMedia\Metadata\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Import ID3 v2 command object
 *
 * @package GravityMedia\Metadata\Console\Command
 */
class ImportId3v2Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('import:id3v2')
            ->setDescription('Import ID3 v2 metadata')
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
        $tag = $metadata->getId3v2Tag();

        $hydrator = new ClassMethods();
        if (isset($data['picture'])) {
            if (null !== $data['picture']['data']) {
                $data['picture']['data'] = base64_decode($data['picture']['data']);
            }
            $data['picture'] = $hydrator->hydrate($data['picture'], new Picture());
        }
        $hydrator->hydrate($data, $tag)->save();

        $output->writeln('<info>Metadata import successful.</info>');
    }
}
