<?php

/**
 * Specify application-specific configuration. These settings can be over-ridden
 * by the local environmental settings, so it's safe to specify default values
 * here.
 */
return [
    /**
     * Define something...
     */
    'assets.test' => '',

    /**
     * Define a list of commands that should be added to the console on initialisation.
     */
    'console.commands' => [
        'LukeZbihlyj\SilexAssets\Console\AssetsDumpCommand',
        'LukeZbihlyj\SilexAssets\Console\AssetsPurgeCommand'
    ],
];
