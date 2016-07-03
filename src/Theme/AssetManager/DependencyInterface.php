<?php

namespace Theme\AssetManager;

use \Zend\View\Helper\Placeholder\Container\AbstractStandalone;

/**
 *
 * @author PedromDev
 */
interface DependencyInterface
{
    /**
     * 
     * @param string $name
     */
    public function setName($name);
    
    /**
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Add a new dependency
     * 
     * @param DependencyInterface $dependency
     * @throws DependencyException
     */
    public function add(DependencyInterface $dependency);
    
    /**
     * 
     * @param string $name
     * @return DependencyInterface
     * @throws DependencyException
     */
    public function get($name);
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function has($name);
    
    /**
     * 
     * @param string $name
     * @throws DependencyException
     * @return boolean
     */
    public function remove($name);
    
    /**
     * 
     * @return DependencyInterface[]
     */
    public function getAll();
    
    /**
     * 
     * @return boolean
     */
    public function hasDependencies();
    
    /**
     * 
     * @return boolean
     */
    public function removeAll();
    
    /**
     * 
     */
    public function enqueue();
}
