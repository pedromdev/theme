<?php

namespace Theme\AssetManager\Asset;

/**
 *
 * @author PedromDev
 */
interface QueueStatus
{
    const REGISTERED = 1;
    
    const ENQUEUED = 2;
    
    const DISPATCHED = 3;
}
