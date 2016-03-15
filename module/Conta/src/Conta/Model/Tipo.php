<?php

/*
  Criado     : 18/09/2015
  Modificado : 18/09/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
  ...
 */


namespace Conta\Model;

use Zend\Stdlib\Hydrator\ClassMethods;


class Tipo
{
    
    public $id;
    public $nome;
    public $criado;
    public $modificado;
    public $situacao;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
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
