<?php

/*
  Criado     : 23/09/2015
  Modificado : 10/12/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
    Toda comunicação com o banco de dados deve ser feita,
    atravez dessa classe.
 */

namespace Cliente\Model\BdTabela;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Cliente\Model\Cliente;

class ClienteTabela extends AbstractTableGateway
{
    
    /*
     * @var string|array|TableIdentifier
     */

    protected $table = "clientes";

    
    
    
    
    /*
     * @param Zend\Db\Adapter\Adapter
    */

    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Cliente());
        $this->initialize();
    }

    
    
    
    
    /*
     * @param  Boolean $paginado
     * @param  Array   $filtro
     * @return Obj | Boolean
     */

    public function buscarTodos($paginado = false, Array $filtro = array())
    {
        $select = new Select();
        $select->from('clientes')
                ->columns(['id', 'nome', 'email', 'criado', 'modificado'])
                ->order('id DESC');

        if (count($filtro) > 0) {
            $select->where($filtro);
        }

        if ($paginado) {
            $paginatorAdapter = new DbSelect(
                    $select, $this->adapter
            );

            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
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
        $id = (int) $id;

        $select = new Select();
        $select->from('clientes')
                ->columns(['id', 'nome', 'email', 'criado', 'modificado'])
                ->where(['id' => $id]);

        $dados = $this->selectWith($select);
        return $dados->current();
    }

    
    
    
    
    /*
     * @param Cliente\Model\Cliente $objClienteForm
     * @return Int
     */

    public function salvar(Cliente $objCliente)
    {
        $arrayDados = array_filter($objCliente->getArrayCopy());
        $id = (int) $objCliente->getId();
        $data = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
       
        if ($id == 0)
        {
            $arrayDados['criado']     = $data->format('Y-m-d H:i:s');
            $arrayDados['modificado'] = $data->format('Y-m-d H:i:s');
            return $this->insert($arrayDados);
        } else
        {
            if ($this->buscarUm($id))
            {
                $arrayDados['modificado'] = $data->format('Y-m-d H:i:s');
                return $this->update($arrayDados, ['id' => $id]);
            }

            throw new \Exception('Registro não encontrado.');
        }
    }

    
    
    
    
    /*
     * @param Int $id
     * @return Int
     */

    public function deletar($id)
    {
        $id = (int) $id;
        return $this->delete(['id' => $id]);
    }

    
    
    
    
    public function ativar()
    {
        
    }
}
