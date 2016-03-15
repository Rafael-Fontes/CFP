<?php

/* 
    Criado     : 08/10/2015
    Modificado : 08/10/2015
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

use Receita\Model\ReceitaSubcategoria;

class ReceitaSubcategoriaTabela extends AbstractTableGateway
{
    
    protected $table = "receitas_subcategorias";
    
    
    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new ReceitaSubcategoria());
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
            $select->from(array('rs' => 'receitas_subcategorias'))
                    ->join(array('rc' => 'receitas_categorias'), 'rs.fk_categoria = rc.id', 
                            array(
                                'rc_nome' => 'nome'
                            ))
                    ->order('rs.id Desc');
      
            if(count($filtro) > 0)
            {
                $arrayFiltro = array(
                    'rc.nome'  => (isset($filtro['categoria']))    ? $filtro['categoria']    .'%' : null,
                    'rs.nome'  => (isset($filtro['subcategoria'])) ? $filtro['subcategoria'] .'%' : null,
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
        $id    = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $dados = $this->select(array(
            'id' => $id
        ));
        
        return $dados->current();
    }
    
    
    
    
    
    /*
     * Metodo responsavel por salvar e editar registros
     * @param Receita\Model\ReceitaSubcategoria $receitaSub
     */
    public function salvar(ReceitaSubcategoria $receitaSub)
    {
        $dados = array_filter(get_object_vars($receitaSub));
        $id    = filter_var($receitaSub->id, FILTER_SANITIZE_NUMBER_INT);
        
        if($id == 0)
        {
            $this->insert($dados);
        }
        else
        {
            if($this->buscarUm($id))
            {
                $this->update($dados, array('id' => $id));
            }
            else
            {
                throw new \Exception('Registro não encontrado.');
            }
        }
    }
    
    
    
    
    
    /*
     * @param Int $id
    */    
    public function deletar($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->delete(array('id' => $id));
    }
}
