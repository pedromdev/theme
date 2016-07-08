<?php

namespace ThemeTest\AssetManager\Asset;
use \Theme\AssetManager\Asset\Css;

/**
 *
 * @author PedromDev
 */
class CssTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Css
     */
    private $css;
    
    protected function setUp()
    {
        parent::setUp();
        $this->css = new Css('unit-test');
    }
    
    public function testVerificarTipoDoAsset()
    {
        $this->assertEquals('text/css', $this->css->getType());
    }
    
    public function testInserirCaminhoDoArquivo()
    {
        $this->css->setPath('/path/to/style.css');
        
        $this->assertEquals('/path/to/style.css', $this->css->getAttribute('href'));
    }
    
    public function testSeExisteOAtributoId()
    {
        $this->assertTrue($this->css->hasAttribute('id'), 'Um identificador não foi adicionado');
        $this->assertEquals('unit-test', $this->css->getIdentifier());
        $this->assertEquals('unit-test', $this->css->getAttribute('id'));
    }
    
    public function testExibirTagHtml()
    {
        $this->assertEquals("<link type=\"text/css\" id=\"unit-test\"/>", "$this->css");
    }
}
