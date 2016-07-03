<?php

namespace Theme\AssetManager;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author PedromDev
 */
class AssetManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AssetManager();
    }
}
