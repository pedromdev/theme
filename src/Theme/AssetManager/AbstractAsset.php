<?php

namespace Theme\AssetManager;

/**
 *
 * @author PedromDev
 */
abstract class AbstractAsset implements AssetInterface
{
    /**
     *
     * @var string
     */
    private $identifier;
    
    /**
     *
     * @var int
     */
    private $status = 0;
    
    /**
     *
     * @var string
     */
    private $type;
    
    /**
     *
     * @var string
     */
    private $path;
    
    /**
     *
     * @var array
     */
    private $attributes = [];
    
    public function __construct($type, $identifier = null, $status = Asset\QueueStatus::REGISTERED)
    {
        $this->setType($type);
        $this->setIdentifier($identifier);
        $this->setStatus($status);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function setIdentifier($identifier)
    {
        $this->setAttribute('id', $identifier);
        $this->identifier = $identifier;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->setAttribute('type', $type);
        $this->type = $type;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function addAttribute($name, $value)
    {
        if (!is_string($name) || !is_string($value) || $this->hasAttribute($name)) {
            return;
        }
        
        $this->attributes[$this->normalizeAttributeName($name)] = $value;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$this->normalizeAttributeName($name)]);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function removeAttribute($name)
    {
        if (!is_string($name) || !$this->hasAttribute($name)) {
            return;
        }
        
        $attributes = $this->attributes;
        unset($attributes[$this->normalizeAttributeName($name)]);
        $this->attributes = $attributes;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function setAttribute($name, $value)
    {
        $this->removeAttribute($name);
        $this->addAttribute($name, $value);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getAttribute($name)
    {
        if (!$this->hasAttribute($name)) {
            return '';
        }
        
        return $this->attributes[$this->normalizeAttributeName($name)];
    }
    
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function removeAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->removeAttribute($attribute);
        }
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
    
    /**
     * 
     * @param string $name
     * @return string
     * @throws \RuntimeException
     */
    protected function normalizeAttributeName($name) {
        if (!preg_match('/^([a-z0-9._-]+)$/i', $name)) {
            throw new \RuntimeException("Invalid attribute name. Accepted pattern: a-z0-9._-");
        }
        
        return strtolower($name);
    }
}
