<?php

namespace LukeZbihlyj\SilexAssets;

use LukeZbihlyj\SilexPlus\Application;
use LukeZbihlyj\SilexPlus\ModuleInterface;
use Assetic\AssetManager;
use Assetic\FilterManager;
use Assetic\Filter\LessphpFilter;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSqueezeFilter;
use Assetic\Factory\AssetFactory;

/**
 * @package LukeZbihlyj\SilexAssets\Module
 */
class Module implements ModuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigFile()
    {
        return __DIR__ . '/../config/module.php';
    }

    /**
     * {@inheritDoc}
     */
    public function init(Application $app)
    {
        $app['assets'] = $app->share(function() use ($app) {
            $assetManager = new AssetManager();

            $filterManager = new FilterManager();
            $filterManager->set('less', new LessphpFilter());
            $filterManager->set('cssmin', new CssMinFilter());
            $filterManager->set('jsmin', new JSqueezeFilter());

            $factory = new AssetFactory($app['assets.path']);
            $factory->setAssetManager($assetManager);
            $factory->setFilterManager($filterManager);
            $factory->setDefaultOutput('misc/*');

            return $factory;
        });
    }
}
