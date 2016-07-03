<?php

namespace Theme\AssetManager\Asset;

/**
 *
 * @author PedromDev
 */
class Css extends \Theme\AssetManager\AbstractAssetDependency
{
    
    public function __construct($identifier, $status = QueueStatus::REGISTERED) {
        parent::__construct('text/css', $identifier, $status);
    }
    
    public function setPath($path)
    {
        $this->setAttribute('href', $path);
        parent::setPath($path);
    }
    
    public function toString()
    {
        $attributesStr = "";
        foreach ($this->getAttributes() as $key => $value) {
            $attributesStr .= " $key=\"$value\"";
        }
        return "<link$attributesStr/>";
    }
}
