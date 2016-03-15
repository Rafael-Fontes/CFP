<?php

/* 
    Criado     : 18/09/2015
    Modificado : 18/09/2015
    Autor      : Raphaell
    Contato    : rafaelk-f@hotmail.com
    Descrição  :
            Toda comunicação com o banco de dados deve ser feita,
        atravez dessa classe.
*/

namespace Conta\Model\BdTabela;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Conta\Model\Tipo;


class TipoTabela extends AbstractTableGateway
{
    
   protected $table = "tipos";    
    
    
    
    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Tipo());
        $this->initialize();
    }
    
    
    
    
    
    /*
     * @param  Boolean $paginado
     * @param  Array   $filtro
     * @return Obj
    */
    public function buscarTodos($paginado = false, Array $filtro = array())
    {
        if($paginado)
        {
            $select = new Select();
            $select->from('tipos')                    
                    ->order('id DESC');      
            
            if(count($filtro) > 0)
                $select->where($filtro);
             
            $paginatorAdapter = new DbSelect(
                $select,
                $this->adapter
            );
            
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;            
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
     * @param Autenticacao\Model\Tipo $tipo
     */
    public function salvar(Tipo $tipo)
    {
        $dados = array_filter(get_object_vars($tipo));
        $id    = filter_var($tipo->id, FILTER_SANITIZE_NUMBER_INT);
        $data  = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        
        if($id == 0)
        {
            $dados['criado']     = $data->format('Y-m-d H:i:s');
            $dados['modificado'] = $data->format('Y-m-d H:i:s');
            $this->insert($dados);
        }
        else
        {
            if($this->buscarUm($id))
            {
                $dados['modificado'] = $data->format('Y-m-d H:i:s');
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
