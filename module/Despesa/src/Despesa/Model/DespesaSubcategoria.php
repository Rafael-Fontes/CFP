<?php


namespace Despesa\Model;

use Zend\Stdlib\Hydrator\ClassMethods;


/*
  Criado     : 20/11/2015
  Modificado : 20/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
                ...
 */


class DespesaSubcategoria
{    
    
    public $id;
    public $fk_categoria;
    public $nome;
   

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getFkCategoria()
    {
        return $this->fk_categoria;
    }

    public function setFkCategoria($fkCategoria)
    {
        $this->fk_categoria = $fkCategoria;
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
