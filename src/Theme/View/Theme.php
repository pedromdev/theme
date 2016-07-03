<?php

namespace Theme\View;

use \Zend\View\Helper\AbstractHelper;
use \Zend\EventManager\EventManagerInterface;
use \Zend\View\HelperPluginManager;

/**
 *
 * @author PedromDev
 */
class Theme extends AbstractHelper
{
    /**
     *
     * @var HelperPluginManager
     */
    private $helperPluginManager;
    
    /**
     *
     * @var EventManagerInterface
     */
    private $eventManager;
    
    public function __construct(HelperPluginManager $helperPluginManager, EventManagerInterface $eventManager)
    {
        $this->helperPluginManager = $helperPluginManager;
        $this->setEventManager($eventManager);
    }
    
    /**
     * 
     * @return HelperPluginManager
     */
    public function getHelperPluginManager()
    {
        return $this->helperPluginManager;
    }
    
    /**
     * 
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * 
     * @param EventManagerInterface $eventManager
     * @return \Theme\View\Theme
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        
        if (!in_array(__CLASS__, $eventManager->getIdentifiers())) {
            $eventManager->addIdentifiers([
                __CLASS__
            ]);
        }
        return $this;
    }
}
