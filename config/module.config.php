<?php

namespace Theme;

return array(
    'service_manager' => array(
        'aliases' => array(
            'AssetManager' => 'Theme\AssetManager\AssetManager',
            'AssetManagerConfig' => 'Theme\AssetManager\AssetManagerConfig',
        ),
        'factories' => array(
            'Theme\AssetManager\AssetManager' => AssetManager\AssetManagerFactory::class,
            'Theme\AssetManager\AssetManagerConfig' => AssetManager\AssetManagerConfigFactory::class,
        ),
    ),
    
    'view_helpers' => array(
        'factories' => array(
            'theme' => View\ThemeFactory::class,
        ),
    ),
    
    'asset_manager' => array(
        'paths' => array(
            'javascript' => array(),
            'css' => array(),
        ),
        'assets' => array(
            'javascript' => array(),
            'css' => array(),
        ),
    ),
);