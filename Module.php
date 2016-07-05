<?php

namespace Theme;

use \Zend\ModuleManager\Feature\ConfigProviderInterface;
use \Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use \Zend\ModuleManager\Feature\DependencyIndicatorInterface;

/**
 *
 * @author PedromDev
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface, DependencyIndicatorInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getModuleDependencies()
    {
        return [
            'Base'
        ];
    }
}