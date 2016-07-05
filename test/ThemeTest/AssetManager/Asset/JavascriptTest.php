<?php

namespace ThemeTest\AssetManager\Asset;

use \Theme\AssetManager\Asset\Javascript;
use \Theme\AssetManager\Asset\QueueStatus;
use \ThemeTest\AssetManager\Mock\InvalidDependencyMock;

/**
 *
 * @author PedromDev
 */
class JavascriptTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Javascript
     */
    private $javascript;
    
    protected function setUp()
    {
        parent::setUp();
        $this->javascript = new Javascript('unit-test');
    }
    
    public function testVerificarTipoDoAsset()
    {
        $this->assertEquals('text/javascript', $this->javascript->getType());
    }
    
    public function testSeExisteOAtributoId()
    {
        $this->assertTrue($this->javascript->hasAttribute('id'), 'Um identificador não foi adicionado');
        
        $this->assertEquals('unit-test', $this->javascript->getIdentifier());
        $this->assertEquals('unit-test', $this->javascript->getAttribute('id'));
    }
    
    public function testAdicionarAtributoComTipoDeValorNaoAceito()
    {
        $this->javascript->addAttribute('attr', true);
        
        $this->assertEquals('', $this->javascript->getAttribute('attr'));
    }
    
    /**
     * 
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid attribute name. Accepted pattern: a-z0-9._-
     */
    public function testAdicionarAtributoComNomeNaoAceito()
    {
        $this->javascript->addAttribute('at/tr', 'hello-world');
    }
    
    public function testAdicionarESobrescreverAtributos()
    {
        $this->javascript->setAttributes([
            'id' => 'other-unit-test',
            'class' => 'javascript',
        ]);
        
        $this->assertTrue($this->javascript->hasAttribute('class'), 'O atributo class não foi adicionado');
        $this->assertEquals('other-unit-test', $this->javascript->getAttribute('id'));
        $this->assertEquals('javascript', $this->javascript->getAttribute('class'));
    }
    
    public function testRemoverAtributos()
    {
        $this->javascript->removeAttributes(['type']);
        
        $this->assertFalse(in_array('type', $this->javascript->getAttributes()), 'Alguns dos atributos não foram removidos');
    }
    
    public function testRemoverAtributosQueNaoExistem()
    {
        $countAttributes = count($this->javascript->getAttributes());
        $this->javascript->removeAttributes(['class']);
        
        $this->assertEquals($countAttributes, count($this->javascript->getAttributes()));
    }
    
    public function testInserirCaminhoDoArquivo()
    {
        $this->javascript->setPath('/path/to/javascript.js');
        
        $this->assertEquals('/path/to/javascript.js', $this->javascript->getPath());
        $this->assertEquals('<script type="text/javascript" id="unit-test" src="/path/to/javascript.js"></script>', "$this->javascript");
    }
    
    public function testEnfileirarJavascript()
    {
        $this->javascript->enqueue();
        
        $this->assertEquals(QueueStatus::ENQUEUED, $this->javascript->getStatus());
    }
    
    public function testAdicionarDependencia()
    {
        $javacript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javacript2);
        
        $this->assertEquals($javacript2, $this->javascript->get('unit-test2'));
    }
    
    public function testEnfileirarJavascriptComDependencias()
    {
        $javacript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javacript2);
        $this->javascript->enqueue();
        
        $this->assertTrue($this->javascript->hasDependencies(), 'Não foi possível detectar as dependências adicionadas');
        $this->assertEquals(QueueStatus::ENQUEUED, $this->javascript->getStatus());
        $this->assertEquals(QueueStatus::ENQUEUED, $javacript2->getStatus());
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 121
     * @expectedExceptionMessage The 'unit-test2' already exists in 'unit-test'
     */
    public function testAdicionarDependenciaJaInserida()
    {
        $javacript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javacript2);
        $this->javascript->add($javacript2);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 122
     * @expectedExceptionMessage Circular dependency detected: 'unit-test' - 'unit-test2'
     */
    public function testCausarEfeitoDeDependenciaCircular()
    {
        $javacript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javacript2);
        $javacript2->add($this->javascript);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 122
     * @expectedExceptionMessage Circular dependency detected: 'unit-test' - 'unit-test2'
     */
    public function testCausarEfeitoDeDependenciaCircularEmSubdependencias()
    {
        $javacript2 = new Javascript('unit-test2');
        $javacript3 = new Javascript('unit-test3');
        
        $this->javascript->add($javacript3);
        $javacript3->add($javacript2);
        $javacript2->add($this->javascript);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 120
     * @expectedExceptionMessage Couldn't find the following dependency: 'not-found'
     */
    public function testCausarEfeitoDeDependenciaInexistente()
    {
        $this->javascript->get('not-found');
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 120
     * @expectedExceptionMessage Couldn't find the following dependency: 'not-found'
     */
    public function testCausarEfeitoDeDependenciaNaoEncontradaNasDependencias()
    {
        $javacript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javacript2);
        
        $this->javascript->get('not-found');
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 123
     * @expectedExceptionMessage Dependency expected an 'Theme\AssetManager\AbstractAssetDependency' instance, but ThemeTest\AssetManager\Mock\InvalidDependencyMock given
     */
    public function testCausarEfeitoDeDependenciaInvalida()
    {
        $mock = new InvalidDependencyMock();
        
        $this->javascript->add($mock);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 124
     * @expectedExceptionMessage Auto dependency detected: 'unit-test'
     */
    public function testCausarEfeitoDeAutoDependencia()
    {
        $javascript2 = new Javascript('unit-test');
        
        $this->javascript->add($javascript2);
    }
    
    public function testRemoverUmaDependencia()
    {
        $javascript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javascript2);
        
        $this->assertTrue($this->javascript->remove('unit-test2'), 'Não removeu uma dependência existente');
    }
    
    public function testRemoverDependenciaInexistente()
    {
        $this->assertFalse($this->javascript->remove('not-found'), 'Foi removida uma dependência inexistente');
    }
    
    public function testRemoverTodasAsDependencias()
    {
        $javascript2 = new Javascript('unit-test2');
        
        $this->javascript->add($javascript2);
        $countDependencies = count($this->javascript->getAll());
        
        $this->assertTrue($this->javascript->removeAll(), 'Não removeu uma dependência existente');
        $this->assertNotEquals($countDependencies, count($this->javascript->getAll()));
    }
    
    public function testSeNomeDaDependenciaEhOIdentificador()
    {
        $javascript2 = new Javascript('unit-test2');
        
        $this->javascript->setName('other-unit-test');
        
        $this->assertEquals('other-unit-test', $this->javascript->getName());
        $this->assertEquals('unit-test2', $javascript2->getName());
    }
}
