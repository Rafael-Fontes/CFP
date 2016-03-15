<?php

namespace ClienteTest\Model\Filtro;

use PHPUnit_Framework_TestCase;
use ClienteTest\Bootstrap;
//use Cliente\Model\Filtro\AuthFiltro;

class AuthFiltroTest extends PHPUnit_Framework_TestCase
{

    private $instanciaAuthFiltro;

    
    /*
     * Primeiro metodo a ser execultado no teste
     */
    protected function setUp()
    {
        $this->instanciaAuthFiltro =  Bootstrap::getServiceManager()->get('Cliente\Form\AuthForm');
        parent::setUp();
    }


    
    
    
    public function testVefiricaSeFiltrosDeEntradaEstaoDefinidosCorretamente()
    {
        $getInputerFilter = $this->instanciaAuthFiltro->getInputFilter();
        
        $this->assertTrue($getInputerFilter->has('email'), '"email" nao esta definido como filtro');
        $this->assertTrue($getInputerFilter->has('senha'), '"senha" nao esta definido como filtro');
    }
    
    
    
    
    
//    public function testValidacaoComDadosCorretos()
//    {
//        $this->instanciaAuthFiltro->setData([
//            'email' => 'admin@live.com',
//            'senha' => 'abc1234'            
//        ]);
//        
//        var_dump($this->instanciaAuthFiltro->isValid());
//        
//        $this->assertTrue($this->instanciaAuthFiltro->isValid());
//    }
}
