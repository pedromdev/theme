<?php

namespace Theme\AssetManager;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 *
 * @author PedromDev
 */
class AssetManagerConfigFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        
        if (!isset($config['asset_manager'])) {
            throw new ServiceNotCreatedException("'asset_manager' configuration not found");
        }
        return new AssetManagerConfig($config['asset_manager']);
    }
}
