<?php

/**
 * Specify application-specific configuration. These settings can be over-ridden
 * by the local environmental settings, so it's safe to specify default values
 * here.
 */
return [
    /**
     * Define the path to the root directory for all assets stored. This is only used
     * when relative paths are provided to the asset manager.
     */
    'assets.path' => __DIR__ . '/../asset',

    /**
     * Define the path where the assets should be output - this should be a subdirectory
     * under your public root directory.
     */
    'assets.output_path' => __DIR__ . '/../../../public/asset',

    /**
     * Define the path where the assets are available on the webserver, relative to the
     * root directory. If you want you can include a CDN here.
     */
    'assets.output_uri' => '/asset/',

    /**
     * Define some folders to copy to the public directory. You'll probably want to use
     * this for images and fonts.
     */
    'assets.folders' => [],

    /**
     * Define a list of commands that should be added to the console on initialisation.
     */
    'console.commands' => [
        'LukeZbihlyj\SilexAssets\Console\AssetsDumpCommand',
    ],

    /**
     * Configure some Twig extensions that should be available to every template.
     */
    'twig.extensions' => [
        'LukeZbihlyj\SilexAssets\Twig\TwigExtension',
    ],
];
