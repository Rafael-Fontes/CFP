<?php

/*
    Criado     : 20/11/2015
    Modificado : 05/12/2015
    Autor      : Raphaell
    Contato    : rafaelk-f@hotmail.com
    Descrição  :
            Toda comunicação com o banco de dados deve ser feita,
        atravez dessa classe.
*/



namespace Despesa\Model\BdTabela;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Despesa\Model\DespesaCategoria;

class DespesaCategoriaTabela extends AbstractTableGateway
{


    protected $table = 'despesas_categorias';




    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new DespesaCategoria());
        $this->initialize();
    }





    /*
     * @param Boolean $paginado
     * @param Array   $filtro
     * @return Obj|Boolean
    */
    public function buscarTodos($paginado = false, Array $filtro = array())
    {
        $select = new Select();
        $select->from('despesas_categorias')
                ->order('id DESC');

        if(isset($filtro['descricao']) && !empty($filtro['descricao']))
        {
            $descricao = filter_var($filtro['descricao'], FILTER_SANITIZE_STRING);
            $select->where->like('nome', $descricao . '%');
        }

        if($paginado)
        {
            $paginatorAdapter = new DbSelect(
                $select,
                $this->getAdapter()
            );

            $paginado = new Paginator($paginatorAdapter);
            return $paginado;
        }

        $dados = $this->selectWith($select);
        return $dados;
    }





    /*
     * @param  Int $id
     * @return Obj | Boolean
    */
    public function buscarUm($id)
    {
        $id    = (int) $id;
        $dados = $this->select([
            'id' => $id
        ]);

        return $dados->current();
    }




    
     /*
     * Metodo  responsavel por salvar e editar registros
     * @param  Despesa\Model\DespesaCategoria $objDespesaCategoria
     * @return Int
     */
    public function salvar(DespesaCategoria $objDespesaCategoria)
    {
        $arrayDespesaCategoria = array_filter($objDespesaCategoria->getArrayCopy());
        $id = (int) $objDespesaCategoria->id;

        if($id == 0)
        {                        
            return $this->insert($arrayDespesaCategoria);
        }
        else
        {    
            if($this->buscarUm($id))
            {   var_dump($arrayDespesaCategoria); die;
                return $this->update($arrayDespesaCategoria, ['id' => $id]);  
            }

            throw new \Exception('Registro não encontrado.');
        }
    }





    /*
     * @param  Int $id
     * @return Int
    */
    public function deletar($id)
    {
        $id = (int) $id;
        return $this->delete(['id' => $id]);
    }
}