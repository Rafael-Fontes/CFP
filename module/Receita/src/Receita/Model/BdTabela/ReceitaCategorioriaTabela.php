<?php

/*
    Criado     : 06/10/2015
    Modificado : 05/11/2015
    Autor      : Raphaell
    Contato    : rafaelk-f@hotmail.com
    Descrição  :
            Toda comunicação com o banco de dados deve ser feita,
        atravez dessa classe.
*/


namespace Receita\Model\BdTabela;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Receita\Model\ReceitaCategorioria;


class ReceitaCategorioriaTabela extends AbstractTableGateway
{

    protected $table = "receitas_categorias";


    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new ReceitaCategorioria());
        $this->initialize();
    }





    /*
     * @param Boolean $paginado
     * @param Array   $filtro
     * @return Obj
    */
    public function buscarTodos($paginado = false, Array $filtro = array())
    {
        if($paginado)
        {
            $select = new Select();
            $select->from('receitas_categorias')
                    ->order('id Desc');

            if(count($filtro) > 0)
            {
                $arrayFiltro = array(
                    'nome'  => (isset($filtro['nome']))    ? $filtro['nome']   .'%' : null,
                );

                foreach (array_filter($arrayFiltro) as $chave => $valor)
                {
                    $select->where->like($chave, $valor);
                }
            }

            $paginatorAdapter = new DbSelect(
                $select,
                $this->getAdapter()
            );

            $paginado = new Paginator($paginatorAdapter);
            return $paginado;
        }

        $dados = $this->select($filtro);
        return $dados;
    }





    /*
     * @param  Int $id
     * @return Obj | Boolean
    */
    public function buscarUm($id)
    {
        $id    = (int) $id;
        $dados = $this->select(array(
            'id' => $id
        ));

        return $dados->current();
    }





    /*
     * Metodo  responsavel por salvar e editar registros
     * @param  Conta\Model\ReceitaCategorioria $receitaCat
     * @return Int
     */
    public function salvar(ReceitaCategorioria $receitaCat)
    {
        $dados = array_filter($receitaCat->getArrayCopy());
        $id    = (int) $receitaCat->id;

        if($id == 0)
        {
            return $this->insert($dados);
        }
        else
        {
            if($this->buscarUm($id))
            {
                return $this->update($dados, ['id' => $id]);
            }
            else
            {
                throw new \Exception('Registro não encontrado.');
            }
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
