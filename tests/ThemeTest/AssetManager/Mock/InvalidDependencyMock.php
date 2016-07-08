<?php

namespace ThemeTest\AssetManager\Mock;

/**
 *
 * @author PedromDev
 */
class InvalidDependencyMock implements \Theme\AssetManager\DependencyInterface
{
    public function add(\Theme\AssetManager\DependencyInterface $dependency)
    {
        
    }

    public function get($name)
    {
        
    }

    public function getAll()
    {
        
    }

    public function getName()
    {
        
    }

    public function has($name)
    {
        
    }

    public function hasDependencies()
    {
        
    }

    public function remove($name)
    {
        
    }

    public function removeAll()
    {
        
    }

    public function setName($name)
    {
        
    }

    public function dispatch(\Zend\View\Helper\Placeholder\Container\AbstractStandalone $helper)
    {
        
    }

    public function enqueue()
    {
        
    }

}
