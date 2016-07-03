<?php

namespace Theme\AssetManager;

/**
 *
 * @author PedromDev
 */
interface AssetInterface
{
    /**
     * 
     * @param string $identifier
     */
    public function setIdentifier($identifier);
    
    /**
     * 
     * @return string
     */
    public function getIdentifier();
    
    /**
     * 
     * @param int $status
     * @see Asset\QueueStatus
     */
    public function setStatus($status);
    
    /**
     * 
     * @return int
     * @see Asset\QueueStatus
     */
    public function getStatus();
    
    /**
     * 
     * @param string $type
     */
    public function setType($type);
    
    /**
     * 
     * @return string
     */
    public function getType();
    
    /**
     * 
     * @param string $path
     */
    public function setPath($path);
    
    /**
     * 
     * @return string
     */
    public function getPath();
    
    /**
     * 
     * @param string $name
     * @param string $value
     */
    public function addAttribute($name, $value);
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function hasAttribute($name);
    
    /**
     * 
     * @param string $name
     */
    public function removeAttribute($name);
    
    /**
     * 
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value);
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function getAttribute($name);
    
    /**
     * 
     * @param array $attributes
     */
    public function setAttributes(array $attributes);
    
    /**
     * 
     * @param array $attributes
     */
    public function removeAttributes(array $attributes);
    
    /**
     * 
     * @return array
     */
    public function getAttributes();
    
    /**
     * 
     * @return string
     */
    public function toString();
}
