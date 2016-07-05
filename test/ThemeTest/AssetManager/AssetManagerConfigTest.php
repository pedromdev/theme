<?php

namespace ThemeTest\AssetManager;

use \Theme\AssetManager\AssetManagerConfig;
use \Theme\AssetManager\AssetManagerConfigFactory;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author PedromDev
 */
class AssetManagerConfigTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(AssetManagerConfig::class)
            ->setServiceName('AssetManagerConfig')
            ->setServiceLocator(\ThemeTest\Bootstrap::getServiceManager());
    }
    
    /**
     * 
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @expectedExceptionMessage 'asset_manager' configuration not found
     */
    public function testServiceNaoCriadoPorFaltaDeConfiguracoes()
    {
        $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockSemConfig()
        );
    }
    
    public function testSeEncontraUmJavascript()
    {
        $assetManagerConfig = $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockComUmJavascript()
        );
        
        $this->assertTrue($assetManagerConfig->hasJavascript('unit-test'), 'Não encontrou um javascript configurado');
        $this->assertFalse($assetManagerConfig->hasJavascript('not-found'), 'Encontrou um javascript não configurado');
    }
    
    public function testSeEncontraUmCss()
    {
        $assetManagerConfig = $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockComUmCss()
        );
        
        $this->assertTrue($assetManagerConfig->hasCss('unit-test'), 'Não encontrou um css configurado');
        $this->assertFalse($assetManagerConfig->hasCss('not-found'), 'Encontrou um css não configurado');
    }
    
    public function testUmJavascriptConfigurado()
    {
        $assetManagerConfig = $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockComUmJavascript()
        );
        
        $javascript = $assetManagerConfig->getJavascript('unit-test');
        
        $this->assertTrue(is_array($javascript), 'O retorno não foi um array');
        $this->assertEquals('/path/to/javascript.js', $javascript['path']);
    }
    
    public function testUmCssConfigurado()
    {
        $assetManagerConfig = $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockComUmCss()
        );
        
        $css = $assetManagerConfig->getCss('unit-test');
        
        $this->assertTrue(is_array($css), 'O retorno não foi um array');
        $this->assertEquals('/path/to/style.css', $css['path']);
    }
    
    public function testSeVemNuloQuandoNaoEhEncontradoUmAsset()
    {
        $assetManagerConfig = $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockComConfigVazia()
        );
        
        $javascript = $assetManagerConfig->getJavascript('not-found');
        $css = $assetManagerConfig->getCss('not-found');
        
        $this->assertNull($javascript, 'Retornou um javascript não configurado');
        $this->assertNull($css, 'Retornou um css não configurado');
    }
    
    public function testSeRegistrouOsDiretoriosComOsAssets()
    {
        $assetManagerConfig = $this->retornaUmaInstanciaDeAssetManagerConfig(
            $this->serviceManagerMockComDiretorios()
        );
        
        $javascriptFolders = $assetManagerConfig->getJavascriptFolders();
        $cssFolders = $assetManagerConfig->getCssFolders();
        
        $this->assertEquals(1, count($javascriptFolders));
        $this->assertEquals(1, count($cssFolders));
        $this->assertEquals('/path/to/js-folder', $javascriptFolders[0]);
        $this->assertEquals('/path/to/css-folder', $cssFolders[0]);
    }
    
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return AssetManagerConfig
     */
    private function retornaUmaInstanciaDeAssetManagerConfig(ServiceLocatorInterface $serviceLocator)
    {
        $assetManagerConfigFactory = new AssetManagerConfigFactory();
        return $assetManagerConfigFactory->createService($serviceLocator);
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockSemConfig()
    {
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn([]);
        return $mock;
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockComConfigVazia()
    {
        $mock = $this->getMockBuilder('\Zend\ServiceManager\ServiceManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->with('Config')
            ->willReturn([
                'asset_manager' => [],
            ]);
        return $mock;
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockComUmJavascript()
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
                            'unit-test' => [
                                'path' => '/path/to/javascript.js',
                            ],
                        ]
                    ]
                ]
            ]);
        return $mock;
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockComUmCss()
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
                            'unit-test' => [
                                'path' => '/path/to/style.css',
                            ],
                        ]
                    ]
                ]
            ]);
        return $mock;
    }
    
    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function serviceManagerMockComDiretorios()
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
                    'paths' => [
                        'css' => [
                            '/path/to/css-folder',
                        ],
                        'javascript' => [
                            '/path/to/js-folder',
                        ],
                    ],
                ]
            ]);
        return $mock;
    }
}
