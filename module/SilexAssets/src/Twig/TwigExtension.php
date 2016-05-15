<?php

namespace LukeZbihlyj\SilexAssets\Twig;

use Twig_SimpleFunction;
use Assetic\Extension\Twig\AsseticExtension;
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
    public function getFunctions()
    {
        return array_merge(parent::getFunctions(), [
            new Twig_SimpleFunction('cdn', function($outputPath) {
                return rtrim($this->app['assets.output_uri'], '/') . '/' . $outputPath;
            }),
        ]);
    }
}
