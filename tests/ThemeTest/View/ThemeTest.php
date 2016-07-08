<?php

namespace ThemeTest\View;

/**
 *
 * @author PedromDev
 */
class ThemeTest extends \Base\Test\ViewHelperTest
{
    protected function initBeforeSetUp()
    {
        $this->setServiceName('theme')
            ->setClassName('\Theme\View\Theme')
            ->setServiceLocator(\ThemeTest\Bootstrap::getServiceManager()->get('ViewHelperManager'));
    }
    
    public function testSePossuiUmEventManager()
    {
        /* @var $theme \Theme\View\Theme */
        $theme = $this->getService();
        $this->assertInstanceOf('\Zend\EventManager\EventManagerInterface', $theme->getEventManager());
        $identifiers = $theme->getEventManager()->getIdentifiers();
        $this->assertTrue(
            in_array(\Theme\View\Theme::class, $identifiers),
            'O identificador não foi encontrado no EventManager'
        );
    }
    
    public function testSePossuiUmHelperPluginManager()
    {
        /* @var $theme \Theme\View\Theme */
        $theme = $this->getService();
        $this->assertInstanceOf(\Zend\View\HelperPluginManager::class, $theme->getHelperPluginManager());
    }
}
