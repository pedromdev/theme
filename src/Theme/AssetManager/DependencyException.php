<?php

namespace Theme\AssetManager;

/**
 *
 * @author PedromDev
 */
class DependencyException extends \Exception
{
    public static function dependencyNotFound($name)
    {
        return new self("Couldn't find the following dependency: '$name'", 120);
    }
    
    public static function dependencyAlreadyExists($name, $parentName)
    {
        return new self("The '$name' already exists in '$parentName'", 121);
    }
    
    public static function circularDependency($name, $parentName)
    {
        return new self(
            "Circular dependency detected: '$name' - '$parentName'",
            122
        );
    }
    
    public static function invalidDependencyType($accepted, $received)
    {
        return new self(sprintf(
            "Dependency expected an '$accepted' instance, but %s given",
            (is_object($received)? get_class($received) : gettype($received))
        ), 123);
    }
    
    public static function autoDependency($name)
    {
        return new self("Auto dependency detected: '$name'", 124);
    }
}
