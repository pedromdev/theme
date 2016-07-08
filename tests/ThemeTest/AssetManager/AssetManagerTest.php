<?php

namespace ThemeTest\AssetManager;

use \Theme\AssetManager\AssetManager;
use \Theme\AssetManager\AssetManagerFactory;
use \Theme\AssetManager\AssetManagerConfig;
use \Theme\AssetManager\AssetManagerConfigFactory;
use \Theme\AssetManager\Asset\Javascript;
use \Theme\AssetManager\Asset\Css;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author PedromDev
 */
class AssetManagerTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(AssetManager::class)
            ->setServiceName('Theme\AssetManager\AssetManager')
            ->setServiceLocator(\ThemeTest\Bootstrap::getServiceManager());
    }
    
    public function testSePossuiUmaInstanciaDasSuasConfiguracoes()
    {
        $assetManager = $this->getService();
        $this->assertInstanceOf(AssetManagerConfig::class, $assetManager->getConfig());
    }
    
    public function testSeRetornaBootstrapComSuasDependencias()
    {
        $assetManager = $this->retornaUmaInstanciaDeAssetManager(
            $this->serviceManagerMockCom3Javascripts()
        );
        
        $bootstrap = $assetManager->getJavascript('bootstrap');
        $bootstrapDependencies = $bootstrap->getAll();
        $jqueryUi = $assetManager->getJavascript('jquery-ui');
        $jqueryUiDependencies = $jqueryUi->getAll();
        
        $this->assertEquals(1, count($jqueryUiDependencies));
        $this->assertEquals(2, count($bootstrapDependencies));
        $this->assertEquals('js/bootstrap.js', $bootstrap->getPath());
        $this->assertEquals('js/jquery-ui.js', $jqueryUi->getPath());
        $this->assertSame(
            $jqueryUiDependencies['jquery'],
            $bootstrapDependencies['jquery']
        );
        $this->assertSame($jqueryUi, $bootstrapDependencies['jquery-ui']);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\AssetException
     * @expectedExceptionMessage Couldn't find the asset with the name 'not-found'
     */
    public function testSeLancaExcecaoQuandoNaoEncontraUmJavascript()
    {
        $assetManager = $this->retornaUmaInstanciaDeAssetManager(
            $this->serviceManagerMockSemAssets()
        );
        $assetManager->getJavascript('not-found');
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionMessage Circular dependency detected: 'jquery' - 'bootstrap'
     */
    public function testSeLancaExcecaoQuandoEncontraUmaDependenciaCircular()
    {
        $assetManager = $this->retornaUmaInstanciaDeAssetManager(
            $this->serviceManagerMockComDependenciaCircular()
        );
        $assetManager->getJavascript('bootstrap');
    }
    
    public function testSeRetornaEstiloComSuasDependencias()
    {
        $assetManager = $this->retornaUmaInstanciaDeAssetManager(
            $this->serviceManagerMockCom3Css()
        );
        
        $style = $assetManager->getCss('style');
        $styleDependencies = $style->getAll();
        $bootstrapTheme = $assetManager->getCss('bootstrap-theme');
        $bootstrapThemeDependencies = $bootstrapTheme->getAll();
        
        $this->assertEquals(1, count($bootstrapThemeDependencies));
        $this->assertEquals(2, count($styleDependencies));
        $this->assertEquals('css/style.css', $style->getPath());
        $this->assertEquals(
            'css/bootstrap-theme.css',
            $bootstrapTheme->getPath()
        );
        $this->assertSame(
            $bootstrapThemeDependencies['bootstrap'],
            $styleDependencies['bootstrap']
        );
        $this->assertSame(
            $bootstrapTheme,
            $styleDependencies['bootstrap-theme']
        );
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\AssetException
     * @expectedExceptionMessage Couldn't find the asset with the name 'not-found'
     */
    public function testSeLancaExcecaoQuandoNaoEncontraUmCss()
    {
        $assetManager = $this->retornaUmaInstanciaDeAssetManager(
            $this->serviceManagerMockSemAssets()
        );
        $assetManager->getCss('not-found');
    }
    
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return AssetManager
     */
    private function retornaUmaInstanciaDeAssetManager(
        ServiceLocatorInterface $serviceLocator
    ) {
        $assetManagerFactory = new AssetManagerFactory();
        return $assetManagerFactory->createService($serviceLocator);
    }
    
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function retornaUmaInstanciaDeAssetManagerConfigNoServiceManager(
        ServiceLocatorInterface $serviceLocator
    ) {
        $assetManagerConfigFactory = new AssetManagerConfigFactory();
        $instance = $assetManagerConfigFactory->createService($serviceLocator);
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('AssetManagerConfig')
            ->willReturn($instance);
        return $mock;
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockCom3Javascripts()
    {
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn([
                'asset_manager' => [
                    'assets' => [
                        'javascript' => [
                            'jquery' => [
                                'path' => 'js/jquery.js',
                            ],
                            'jquery-ui' => [
                                'path' => 'js/jquery-ui.js',
                                'dependencies' => ['jquery'],
                            ],
                            'bootstrap' => [
                                'path' => 'js/bootstrap.js',
                                'dependencies' => ['jquery', 'jquery-ui'],
                            ],
                        ],
                    ],
                ]
            ]);
        return $this->retornaUmaInstanciaDeAssetManagerConfigNoServiceManager($mock);
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockSemAssets()
    {
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn([
                'asset_manager' => [
                    'assets' => [],
                ]
            ]);
        return $this->retornaUmaInstanciaDeAssetManagerConfigNoServiceManager($mock);
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockComDependenciaCircular()
    {
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn([
                'asset_manager' => [
                    'assets' => [
                        'javascript' => [
                            'jquery' => [
                                'path' => 'js/jquery.js',
                                'dependencies' => ['bootstrap'],
                            ],
                            'bootstrap' => [
                                'path' => 'js/bootstrap.js',
                                'dependencies' => ['jquery'],
                            ],
                        ],
                    ],
                ]
            ]);
        return $this->retornaUmaInstanciaDeAssetManagerConfigNoServiceManager($mock);
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockCom3Css()
    {
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn([
                'asset_manager' => [
                    'assets' => [
                        'css' => [
                            'bootstrap' => [
                                'path' => 'css/bootstrap.css',
                            ],
                            'bootstrap-theme' => [
                                'path' => 'css/bootstrap-theme.css',
                                'dependencies' => ['bootstrap'],
                            ],
                            'style' => [
                                'path' => 'css/style.css',
                                'dependencies' => ['bootstrap', 'bootstrap-theme'],
                            ],
                        ],
                    ],
                ]
            ]);
        return $this->retornaUmaInstanciaDeAssetManagerConfigNoServiceManager($mock);
    }
}
