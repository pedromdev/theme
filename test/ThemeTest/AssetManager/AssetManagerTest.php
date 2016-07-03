<?php

namespace ThemeTest\AssetManager;

/**
 *
 * @author PedromDev
 */
class AssetManagerTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(\Theme\AssetManager\AssetManager::class)
            ->setServiceName('Theme\AssetManager\AssetManager')
            ->setServiceLocator(\ThemeTest\Bootstrap::getServiceManager());
    }
}
