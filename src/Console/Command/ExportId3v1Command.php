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
 * Export ID3 V1 command
 *
 * @package GravityMedia\MediaTags\Console\Command
 */
class ExportId3v1Command extends Command
{
    const FORMAT_YAML = 'yaml';

    protected function configure()
    {
        $this
            ->setName('export:id3v1')
            ->setDescription('Export ID3 V1 media tag')
            ->addArgument(
                'input',
                InputArgument::REQUIRED,
                'The name of the input file'
            )
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'The name of the export file'
            )
            ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                'The export format (e.g. YAML)',
                self::FORMAT_YAML
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFile = new SplFileInfo($input->getArgument('input'));

        $mediaTags = $inputFile->getMediaTags();
        $tag = $mediaTags->getId3v1();

        $hydrator = new ClassMethods();
        $data = $hydrator->extract($tag);
        $data['audio_properties'] = $hydrator->extract($data['audio_properties']);
        foreach (array_keys($data) as $name) {
            if (null === $data[$name]) {
                unset($data[$name]);
            }
        }

        $format = $input->getOption('format');
        if (!in_array($format, array(self::FORMAT_YAML))) {
            $format = self::FORMAT_YAML;
        }

        switch($format) {
            case self::FORMAT_YAML:
                $data = Yaml::dump($data);
                break;
        }

        $outputFilename = $input->getArgument('output');
        if (null === $outputFilename) {
            $output->writeln('<info>' . $data . '</info>');
            return;
        }

        if (file_put_contents($outputFilename, $data)) {
            $output->writeln(sprintf('<info>Tag file \'%s\' successfully exported.</info>', $outputFilename));
            return;
        }

        $output->writeln(sprintf('<error>Unable to write tag export file \'%s\'.</error>', $outputFilename));
    }
}
