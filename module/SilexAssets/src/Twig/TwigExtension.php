<?php

namespace LukeZbihlyj\SilexAssets\Twig;

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
}
