<?php

namespace Theme\AssetManager\Asset;

/**
 *
 * @author PedromDev
 */
class Javascript extends \Theme\AssetManager\AbstractAssetDependency
{
    /**
     * 
     * {@inheritDoc}
     */
    public function setPath($path)
    {
        $this->setAttribute('src', $path);
        parent::setPath($path);
    }
    
    public function __construct($identifier, $status = QueueStatus::REGISTERED)
    {
        parent::__construct('text/javascript', $identifier, $status);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function toString()
    {
        $attributesStr = "";
        foreach ($this->getAttributes() as $key => $value) {
            $attributesStr .= " $key=\"$value\"";
        }
        return "<script$attributesStr></script>";
    }
}
