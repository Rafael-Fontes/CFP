<?php

namespace ClienteTest\ModelTest;

use PHPUnit_Framework_TestCase;
use Cliente\Model\Cliente;


class ClienteTest extends PHPUnit_Framework_TestCase
{
    
    private $instanciaDoCliente;
    
    
    /*
     * @return Cliente\Model\Cliente;
     */
    public function getInstanciaDoCliente()
    {
        if(!$this->instanciaDoCliente){
            $this->instanciaDoCliente = new Cliente();
        }
        
        return $this->instanciaDoCliente;
    }

    
    
    /*
     * @return Array
     */
    public function getObjCliente()
    {
        $dados = [
            'id'            => '1',
            'nome'          => 'Usúario de teste',
            'email'         => 'usuarioteste@live.com',
            'senha'         => 'laskdjfoiw0809879ijasdjfku78976iusydfiuhjf9789798',
            'salt'          => 'lsadkfjiou98798798',
            'criado'        => '2015-01-22 14:41:00',
            'modificado'    => '2015-01-22 14:41:00',
            'situacao'      => 'A',
        ];
        
        return $dados;
    }

    
    
    /*
     * Vefifica se ao estanciar a classe Cliente\Model\Cliente, todos os
     * Seus atributos são iniciados como null.
     */
    public function testEstadoInicialDoObjetoCliente()
    {
        $cliente = $this->getInstanciaDoCliente();
        
        $this->assertNull($cliente->getId(),          '"getId()" deve ser inicialmete null');        
        $this->assertNull($cliente->getNome(),        '"getNome()" deve ser inicialmete null');        
        $this->assertNull($cliente->getEmail(),       '"getEmail()" deve ser inicialmete null');        
        $this->assertNull($cliente->getSenha(),       '"getSenha()" deve ser inicialmete null');        
        $this->assertNull($cliente->getSalt(),        '"getSalt()" deve ser inicialmete null');        
        $this->assertNull($cliente->getCriado(),      '"getCriado()" deve ser inicialmete null');        
        $this->assertNull($cliente->getModificado(),  '"getModificado()" deve ser inicialmete null');        
        $this->assertNull($cliente->getSituacao(),    '"getSituacao()" deve ser inicialmete null');        
    }
    
    

    
    
    /*
     * Verifica se o metodo exchangeArray esta funcionando corretamente.
     */
    public function testDoMetodoExchangeArray()
    {
        $cliente = $this->getInstanciaDoCliente();
        $dados   = $this->getObjCliente();
        
        $cliente->exchangeArray($dados);
        
        $this->assertSame($dados['id'],         $cliente->getId(),            '"id" não foi definido corretamente');
        $this->assertSame($dados['nome'],       $cliente->getNome(),          '"nome" não foi definido corretamente');
        $this->assertSame($dados['email'],      $cliente->getEmail(),         '"email" não foi definido corretamente');
        $this->assertSame($dados['senha'],      $cliente->getSenha(),         '"senha" não foi definido corretamente');
        $this->assertSame($dados['salt'],       $cliente->getSalt(),          '"salt" não foi definido corretamente');
        $this->assertSame($dados['criado'],     $cliente->getCriado(),        '"criado" não foi definido corretamente');
        $this->assertSame($dados['modificado'], $cliente->getModificado(),    '"criado" não foi definido corretamente');
        $this->assertSame($dados['situacao'],   $cliente->getSituacao(),      '"situacao" não foi definido corretamente');
    }
    
    
    
    
    
    public function testDoMetodoGetArrayCopy()
    {
        $cliente = $this->getInstanciaDoCliente();
        $dados   = $this->getObjCliente();
        
        $cliente->exchangeArray($dados);
        $clienteArray = $cliente->getArrayCopy();
        
        $this->assertSame($dados['id'],         $clienteArray['id'],        '"id" não foi definido corretamente');
        $this->assertSame($dados['nome'],       $clienteArray['nome'],      '"nome" não foi definido corretamente');
        $this->assertSame($dados['email'],      $clienteArray['email'],     '"email" não foi definido corretamente');
        $this->assertSame($dados['senha'],      $clienteArray['senha'],     '"senha" não foi definido corretamente');
        $this->assertSame($dados['salt'],       $clienteArray['salt'],      '"salt" não foi definido corretamente');
        $this->assertSame($dados['criado'],     $clienteArray['criado'],    '"criado" não foi definido corretamente');
        $this->assertSame($dados['modificado'], $clienteArray['modificado'],'"modificado" não foi definido corretamente');
        $this->assertSame($dados['situacao'],   $clienteArray['situacao'],  '"situacao" não foi definido corretamente');
    }
      
}
