<?php

namespace LukeZbihlyj\SilexAssets\Twig;

use Twig_SimpleFunction;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\Extension\Twig\AsseticTokenParser;
use Silex\Application;

/**
 * @package LukeZbihlyj\SilexAssets\Twig\TwigExtension
 */
class TwigExtension extends AsseticExtension
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     * @return self
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        return parent::__construct($app->getAssets());
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenParsers()
    {
        return [
            new AsseticTokenParser($this->factory, 'javascripts', 'js/*.js', false, ['module']),
            new AsseticTokenParser($this->factory, 'stylesheets', 'css/*.css', false, ['module']),
            new AsseticTokenParser($this->factory, 'image', 'images/*', true, ['module']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array_merge(parent::getFunctions(), [
            new Twig_SimpleFunction('cdn', function($outputPath) {
                $basePath = rtrim($this->app['assets.output_path']);
                $assetPath = $basePath . '/' . $outputPath;
                $assetModified = file_exists($assetPath) ? filemtime($assetPath) : null;
                $cacheBuster = substr(sha1($assetModified), 0, 7);

                return rtrim($this->app['assets.output_uri'], '/') . '/' . $outputPath . '?' . $cacheBuster;
            }),
        ]);
    }
}
