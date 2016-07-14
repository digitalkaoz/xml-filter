<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Console;

use Rs\XmlFilter\Loader\JsonLoader;
use Rs\XmlFilter\Loader\Loader;
use Rs\XmlFilter\Loader\YamlLoader;
use Rs\XmlFilter\XmlFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class FilterCommand extends Command
{
    /**
     * @var XmlFilter
     */
    private $filter;

    public function __construct(XmlFilter $filter)
    {
        $this->filter = $filter;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('filter')
            ->setDescription('filters an xml file|string|stdin with a given config and returns json or dumps the result')
            ->setDefinition([
                new InputOption('input', null, InputOption::VALUE_REQUIRED, 'which input to use [file|string|stdin]', 'stdin'),
                new InputOption('output', null, InputOption::VALUE_REQUIRED, 'which output format to use [json|dump]', 'dump'),
                new InputArgument('config', InputArgument::REQUIRED, 'the config file'),
            ])
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $content = $input->getOption('input');

        if ('stdin' === $input->getOption('input')) {
            $content = stream_get_contents(STDIN);
        }

        $document = $this->filter->load($content);
        $loader = $this->createLoader($input->getArgument('config'));

        $result = $this->filter->filter($document, $loader);

        if ('json' === $input->getOption('output')) {
            return $output->writeln(json_encode($result));
        }

        return dump($result);
    }

    private function createLoader($configFile) : Loader
    {
        json_decode(file_get_contents($configFile));

        if (!json_last_error()) {
            return new JsonLoader($configFile);
        }

        Yaml::parse(file_get_contents($configFile));

        return new YamlLoader($configFile);
    }
}
