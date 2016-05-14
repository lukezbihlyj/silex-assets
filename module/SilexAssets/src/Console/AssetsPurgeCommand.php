<?php

namespace LukeZbihlyj\SilexAssets\Console;

use LukeZbihlyj\SilexPlus\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package LukeZbihlyj\SilexAssets\Console\AssetsPurgeCommand
 */
class AssetsPurgeCommand extends ConsoleCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('assets:purge')
            ->setDescription('Purge all generated assets from the public asset directory.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApp();

        //$output->writeln('<comment>Found entity ' . $entity . ', running migration...</comment>');

        $output->writeln('<info>Finished purging!</info>');
    }
}
