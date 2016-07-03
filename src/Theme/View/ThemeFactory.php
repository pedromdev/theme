<?php

namespace Theme\View;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author PedromDev
 */
class ThemeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $eventManager = $serviceLocator->getServiceLocator()->get('Application')->getEventManager();
        return new Theme($serviceLocator, $eventManager);
    }
}
