<?php

/*
  Criado     : 22/09/2015
  Modificado : 22/09/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
  ...
 */


namespace Conta\Model;

use Zend\Stdlib\Hydrator\ClassMethods;


class Conta
{
    private $id;
    private $fk_tipo;
    private $fk_cliente;
    private $nome;
    private $saldo;
    private $criado;
    private $modificado;
    
    
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getFkTipo()
    {
        return $this->fk_tipo;
    }

    public function setFkTipo($fkTipo)
    {
        $this->fk_tipo = $fkTipo;
        return $this;
    }

    public function getFkCliente()
    {
        return $this->fk_cliente;
    }

    public function setFkCliente($fkCliente)
    {
        $this->fk_cliente = $fkCliente;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }
    
    public function getSaldo()
    {
        return $this->saldo;
    }

    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
        return $this;
    }
    
    public function getCriado()
    {
        return $this->criado;
    }

    public function setCriado($criado)
    {
        $this->criado = $criado;
        return $this;
    }

    public function getModificado()
    {
        return $this->modificado;
    }

    public function setModificado($modificado)
    {
        $this->modificado = $modificado;
        return $this;
    }

    
    
    
    /*
     * @param Array $dados
     * @return Obj
     */

    public function exchangeArray(Array $dados = array())
    {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($dados, $this);
    }

    
    
    /*
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
