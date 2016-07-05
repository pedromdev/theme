<?php

namespace Theme\AssetManager;

/**
 *
 * @author PedromDev
 */
class AssetException extends \Exception
{
    public static function notFound($name)
    {
        return new self("Couldn't find the asset with the name '$name'", 120);
    }
}
