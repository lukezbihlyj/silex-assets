<?php

namespace LukeZbihlyj\SilexAssets\Console;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use LukeZbihlyj\SilexPlus\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Assetic\AssetWriter;
use Assetic\Factory\LazyAssetManager;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Extension\Twig\TwigResource;
use Assetic\Util\VarUtils;

/**
 * @package LukeZbihlyj\SilexAssets\Console\AssetsDumpCommand
 */
class AssetsDumpCommand extends ConsoleCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('assets:dump')
            ->setDescription('Dump all found assets into the public asset directory.')
            ->setDefinition([
                new InputOption('ignore-folders', null, InputOption::VALUE_NONE)
            ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApp();
        $twig = $app->getTwig();
        $factory = $app->getAssets();

        $assetManager = new LazyAssetManager($factory);
        $assetManager->setLoader('twig', new TwigFormulaLoader($twig));

        if ($input->getOption('ignore-folders')) {
            $output->writeln('<comment>' . date('H:i:s') . '</comment> <info>Skipping folders...</info>');
        } else {
            foreach ($app['assets.folders'] as $folder) {
                $source = $folder['source'];
                $target = $folder['target'];

                if (!is_dir($target)) {
                    $output->writeln('<comment>' . date('H:i:s') . '</comment> <info>[dir+]</info> ' . realpath($target));

                    if (false === @mkdir($target, 0777, true)) {
                        throw new \RuntimeException('Unable to create directory ' . $target);
                    }
                }

                $directoryIterator = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
                $iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);

                foreach ($iterator as $file) {
                    $path = $target . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

                    if ($file->isDir()) {
                        if (is_dir($path)) {
                            continue;
                        }

                        $output->writeln('<comment>' . date('H:i:s') . '</comment> <info>[dir+]</info> ' . $this->getAbsolutePath($path));

                        if (false === @mkdir($path, 0777, true)) {
                            throw new \RuntimeException('Unable to create directory ' . $path);
                        }
                    } else {
                        if (is_file($path) && md5_file($path) == md5_file($file)) {
                            continue;
                        }

                        $output->writeln('<comment>' . date('H:i:s') . '</comment> <info>[file+]</info> ' . $this->getAbsolutePath($path));

                        if (false === @file_put_contents($path, file_get_contents($file))) {
                            throw new \RuntimeException('Unable to write file ' . $path);
                        }
                    }
                }
            }
        }

        $directoryIterator = new RecursiveDirectoryIterator($app['twig.path']);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        $templates = new RegexIterator($iterator, '/^.+\.twig$/i', RegexIterator::GET_MATCH);

        foreach ($templates as $file) {
            $file = str_replace(rtrim($app['twig.path'], '/') . '/', null, $file[0]);
            $resource = new TwigResource($twig->getLoader(), $file);
            $assetManager->addResource($resource, 'twig');
        }

        $writer = new AssetWriter($app['assets.output_path']);

        foreach ($assetManager->getNames() as $name) {
            $asset = $assetManager->get($name);

            foreach (VarUtils::getCombinations($asset->getVars(), []) as $combination) {
                $asset->setValues($combination);

                $path = $app['assets.output_path'] . '/' . VarUtils::resolve(
                    $asset->getTargetPath(),
                    $asset->getVars(),
                    $asset->getValues()
                );

                if (!is_dir($dir = dirname($path))) {
                    $output->writeln('<comment>' . date('H:i:s') . '</comment> <info>[dir+]</info> ' . $this->getAbsolutePath($dir));

                    if (false === @mkdir($dir, 0777, true)) {
                        throw new \RuntimeException('Unable to create directory ' . $dir);
                    }
                }

                $output->writeln('<comment>' . date('H:i:s') . '</comment> <info>[file+]</info> ' . $this->getAbsolutePath($path));

                if (false === @file_put_contents($path, $asset->dump())) {
                    throw new \RuntimeException('Unable to write file ' . $path);
                }
            }
        }
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getAbsolutePath($path)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = [];

        foreach ($parts as $part) {
            if ($part == '.') {
                continue;
            }

            if ($part == '..') {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }

        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }
}
