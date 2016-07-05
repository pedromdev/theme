<?php

namespace Theme\AssetManager;

use \Zend\Stdlib\ArrayUtils;

/**
 *
 * @author PedromDev
 */
class AssetManagerConfig
{
    /**
     *
     * @var array
     */
    private $javascript = [];
    
    /**
     *
     * @var array
     */
    private $css = [];
    
    /**
     *
     * @var array
     */
    private $paths = [];
    
    public function __construct(array $config)
    {
        $assets = isset($config['assets']) ? $config['assets'] : [];
        $this->paths = isset($config['paths']) && is_array($config['paths']) ?
            $config['paths'] : [];
        
        if (isset($assets['javascript']) && is_array($assets['javascript'])) {
            $this->javascript = ArrayUtils::merge(
                $this->javascript,
                $assets['javascript']
            );
        }
        
        if (isset($assets['css']) && is_array($assets['css'])) {
            $this->css = ArrayUtils::merge($this->css, $assets['css']);
        }
    }
    
    /**
     * Check if javascript exists
     * 
     * @param string $name
     * @return boolean
     */
    public function hasJavascript($name)
    {
        return isset($this->javascript[$name]);
    }
    
    /**
     * Check if css exists
     * 
     * @param string $name
     * @return boolean
     */
    public function hasCss($name)
    {
        return isset($this->css[$name]);
    }
    
    /**
     * Returns an array with Javascript data
     * 
     * @param string $name
     * @return array|null
     */
    public function getJavascript($name)
    {
        if (!$this->hasJavascript($name)) {
            return null;
        }
        return $this->javascript[$name];
    }
    
    /**
     * Returns an array with Css data
     * 
     * @param string $name
     * @return array|null
     */
    public function getCss($name)
    {
        if (!$this->hasCss($name)) {
            return null;
        }
        return $this->css[$name];
    }
    
    /**
     * Returns an array containing the Javascript folders
     * 
     * @return array
     */
    public function getJavascriptFolders()
    {
        return $this->paths['javascript'];
    }
    
    /**
     * Returns an array containing the Css folders
     * 
     * @return array
     */
    public function getCssFolders()
    {
        return $this->paths['css'];
    }
}
