<?php
/**
 * This file is part of the media tags package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\MediaTags\Console\Command;

use GravityMedia\MediaTags\Meta\Picture;
use GravityMedia\MediaTags\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Import ID3 V2 command
 *
 * @package GravityMedia\MediaTags\Console\Command
 */
class ImportId3v2Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('import:id3v2')
            ->setDescription('Import ID3 V2 media tag')
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

        $mediaTags = $file->getMediaTags();
        $tag = $mediaTags->getId3v2();

        $hydrator = new ClassMethods();
        if (isset($data['picture'])) {
            if (null !== $data['picture']['data']) {
                $data['picture']['data'] = base64_decode($data['picture']['data']);
            }
            $data['picture'] = $hydrator->hydrate($data['picture'], new Picture());
        }
        $hydrator->hydrate($data, $tag)->save();

        $output->writeln('<info>Tag import successful.</info>');
    }
}
