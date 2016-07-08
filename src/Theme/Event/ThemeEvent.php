<?php

namespace Theme\Event;

use \Zend\EventManager\Event;
use \Zend\Mvc\MvcEvent;
use \Zend\View\Model\ModelInterface;

/**
 *
 * @author PedromDev
 */
class ThemeEvent extends Event
{
    const EVENT_GET_HEADER = 'theme.get_header';
    const EVENT_GET_FOOTER = 'theme.get_footer';
    const EVENT_GET_SIDEBAR = 'theme.get_sidebar_';
    const EVENT_ENQUEUE_SCRIPTS = 'theme.enqueue_scripts';

    /**
     *
     * @var MvcEvent
     */
    private $mvcEvent;
    
    /**
     *
     * @var ModelInterface
     */
    private $model;
    
    /**
     * 
     * @return MvcEvent
     */
    public function getMvcEvent()
    {
        return $this->mvcEvent;
    }

    /**
     * 
     * @param MvcEvent $mvcEvent
     * @return \Theme\Event\ThemeEvent
     */
    public function setMvcEvent(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
        return $this;
    }
    
    /**
     * 
     * @return ModelInterface
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * 
     * @param ModelInterface $model
     * @return \Theme\Event\ThemeEvent
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
        return $this;
    }
}
