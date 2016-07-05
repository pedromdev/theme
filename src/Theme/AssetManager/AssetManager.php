<?php

namespace Theme\AssetManager;

use \Theme\AssetManager\Asset\Javascript;
use \Theme\AssetManager\Asset\Css;
use \Theme\AssetManager\AssetException;
use Theme\AssetManager\AbstractAsset;
use Theme\AssetManager\AbstractAssetDependency;

/**
 *
 * @author PedromDev
 */
class AssetManager
{
    
    /**
     *
     * @var AssetManagerConfig
     */
    private $config;
    
    /**
     *
     * @var array
     */
    private $instances = [
        'javascript' => [],
        'css' => [],
    ];


    public function __construct(AssetManagerConfig $config)
    {
        $this->config = $config;
    }
    
    /**
     * 
     * @return AssetManagerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Returns a Javascript object
     * 
     * @param string $name
     * @return Javascript
     * @throws AssetException
     */
    public function getJavascript($name)
    {
        if (!$this->getConfig()->hasJavascript($name)) {
            throw AssetException::notFound($name);
        }
        
        if ($this->hasInstance($name, 'javascript')) {
            return $this->getInstance($name, 'javascript');
        }
        return $this->getNewInstance($name, Javascript::class);
    }
    
    /**
     * Returns a Css object
     * 
     * @param string $name
     * @return Javascript
     * @throws AssetException
     */
    public function getCss($name)
    {
        if (!$this->getConfig()->hasCss($name)) {
            throw AssetException::notFound($name);
        }
        
        if ($this->hasInstance($name, 'css')) {
            return $this->getInstance($name, 'css');
        }
        return $this->getNewInstance($name, Css::class);
    }
    
    /**
     * 
     * @param string $name
     * @param string $assetName
     * @return boolean
     */
    protected function hasInstance($name, $assetName)
    {
        return isset($this->instances[$assetName][$name]);
    }
    
    /**
     * 
     * @param string $name
     * @param string $assetName
     * @return Javascript
     */
    protected function getInstance($name, $assetName)
    {
        return $this->instances[$assetName][$name];
    }
    
    /**
     * 
     * @param string $name
     * @param string $assetName
     * @param Javascript $instance
     */
    protected function setInstance(
        $name,
        $assetName,
        AbstractAssetDependency $instance
    ) {
        $this->instances[$assetName][$name] = $instance;
    }
    
    /**
     * 
     * @param string $name
     * @param string $assetName
     */
    protected function removeInstance($name, $assetName)
    {
        $assets = $this->instances[$assetName];
        unset($assets[$name]);
        $this->instances[$assetName] = $assets;
    }
    
    /**
     * Returns a new instance of \Theme\AssetManager\AbstractAssetDependency
     * 
     * @param string $name
     * @param string $className
     * @return AbstractAssetDependency
     */
    private function getNewInstance($name, $className)
    {
        $classNameExploded = explode("\\", $className);
        $shortClassName = strtolower(end($classNameExploded));
        $configMethodName = "get{$shortClassName}";
        $config = $this->getConfig()->$configMethodName($name);
        try {
            $asset = new $className($name);
            $this->setInstance($name, $shortClassName, $asset);
            $this->injectConfig($asset, $config, $shortClassName);
        } catch (\Exception $exc) {
            if ($this->hasInstance($name, $shortClassName)) {
                $this->removeInstance($name, $shortClassName);
            }
            throw $exc;
        }
        return $asset;
    }
    
    /**
     * 
     * @param AbstractAssetDependency $asset
     * @param array $config
     * @param string $assetType
     */
    private function injectConfig(
        AbstractAssetDependency $asset,
        array $config,
        $assetType
    ) {
        $this->injectDependencies($asset, $config, $assetType);
        $this->injectAttributes($asset, $config);
    }
    
    /**
     * Inject attributes in the asset
     * 
     * @param AbstractAsset $asset
     * @param array $attributes
     */
    private function injectAttributes(AbstractAsset $asset, array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $method = 'set' . ucwords(strtolower($name));
            $asset->$method($value);
        }
    }
    
    /**
     * Inject dependencies in the asset
     * 
     * @param AbstractAssetDependency $asset
     * @param array $config
     * @param string $assetType
     */
    private function injectDependencies(
        AbstractAssetDependency $asset,
        array &$config,
        $assetType
    ) {
        if ($this->hasDependencies($config)) {
            $this->setDependencies($asset, $config['dependencies'], $assetType);
            unset($config['dependencies']);
        }
    }
    
    /**
     * Checks if has any dependencies
     * 
     * @param array $asset
     * @return boolean
     */
    private function hasDependencies(array $asset)
    {
        return isset($asset['dependencies']) &&
            is_array($asset['dependencies']);
    }
    
    /**
     * 
     * @param AbstractAssetDependency $javascript
     * @param array $dependencies
     * @param string $assetType
     */
    protected function setDependencies(
        AbstractAssetDependency $javascript,
        array $dependencies,
        $assetType
    ) {
        $methodName = 'get' . ucfirst($assetType);
        foreach ($dependencies as $dependency) {
            $obj = $this->$methodName($dependency);
            $javascript->add($obj);
        }
    }
}
