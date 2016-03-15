<?php

/*
    Criado     : 22/09/2015
    Modificado : 18/11/2015
    Autor      : Raphaell
    Contato    : rafaelk-f@hotmail.com
    Descrição  :
            Toda comunicação com o banco de dados deve ser feita,
        atravez dessa classe.
*/


namespace Conta\Model\BdTabela;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Conta\Model\Conta;

class ContaTabela extends AbstractTableGateway
{

    protected $table = "contas";


    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Conta());
        $this->initialize();
    }





    /*
     * @param Boolean $paginado
     * @param Array   $filtro
     * @return Obj
    */
    public function buscarTodos($paginado = false, Array $filtro = array())
    {
        $sqlReceita = new Select();
        $sqlReceita->from('receitas')
                   ->columns(['receita_valor' => new Expression('IF(ISNULL (SUM(valor)), 0, SUM(valor))')])
                   ->where(['pagamento' => 'N'])
                   ->where('fk_conta = contas.id');

        $sqlDespesa = new Select();
        $sqlDespesa->from('despesas')
                   ->columns(['despesa_valor' => new Expression('IF(ISNULL (SUM(valor)), 0, SUM(valor))')])
                   ->where(['pagamento' => 'N'])
                   ->where('fk_conta = contas.id');


        $select = new Select();
        $select->from('contas')
                ->columns([
                    'id',         'fk_tipo',
                    'fk_cliente', 'nome',
                    'saldo',      'criado',
                    'modificado',
                    'receita_valor' => new Expression('?', [$sqlReceita]),
                    'despesa_valor' => new Expression('?', [$sqlDespesa]),
                ])
                ->join('tipos', 'contas.fk_tipo = tipos.id', array('tipo_nome' => 'nome'))
                ->join('clientes', 'contas.fk_cliente = clientes.id', array('cliente_nome' => 'nome'))
                ->order('clientes.id Desc');


        if (count($filtro) > 0)
        {
            $arrayFiltro = array(
                'tipos.nome'   => (isset($filtro['tipo']))   ? filter_var($filtro['tipo'],  FILTER_SANITIZE_STRING) . '%' : null,
                'contas.nome'  => (isset($filtro['nome']))   ? filter_var($filtro['nome'],  FILTER_SANITIZE_STRING) . '%' : null,
                'contas.saldo' => (isset($filtro['saldo']))  ? filter_var($filtro['saldo'], FILTER_SANITIZE_STRING)       : null,
                'contas.saldo' => (isset($filtro['saldo2'])) ? filter_var($filtro['saldo2'],FILTER_SANITIZE_STRING)       : null,
            );

            foreach (array_filter($arrayFiltro) as $chave => $valor) {
                $select->where->like($chave, $valor);
            }

            if (isset($filtro['data'])) {
                $select->where->greaterThanOrEqualTo(
                        NEW Expression("DATE_FORMAT(?, '%Y-%m')", $filtro['data']), NEW Expression("DATE_FORMAT(contas.criado, '%Y-%m')")
                );
            }
        }

        if ($paginado)
        {
            $paginatorAdapter = new DbSelect(
                $select, $this->getAdapter()
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
        $id     = (int) $id;

        $select = new Select();
        $select->from('contas')
                ->columns(array(
                    'id', 'fk_tipo', 'fk_cliente', 'nome',
                    'saldo' => new Expression("FORMAT(saldo, 2, 'de_DE')"),
                    'criado', 'modificado'
                ))
                ->where(array('id' => $id));

        $dados = $this->selectWith($select);

        return $dados->current();
    }





    /*
     * Metodo  responsavel por salvar e editar registros
     * @param  Conta\Model\Conta $objContaForm
     * @return Int
     */
    public function salvar(Conta $objContaForm)
    {
        $arrayDados = array_filter($objContaForm->getArrayCopy());
        $data       = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $id         = (int) $objContaForm->getId();
        
        if (!empty($objContaForm->getSaldo())) {
            if (substr_count($objContaForm->getSaldo(), ',') > 0) {
                $arrayDados['saldo'] = str_replace(['.', ','], ['', '.'], $objContaForm->getSaldo());
            }
        } else {
            $arrayDados['saldo'] = '0.00';
        }

        if($id == 0)
        {
            $arrayDados['criado']     = $data->format("Y-m-d H:i:s");
            $arrayDados['modificado'] = $data->format("Y-m-d H:i:s");
            return $this->insert($arrayDados);
        }
        else
        {
            if($this->buscarUm($id))
            {
                $arrayDados['modificado'] = $data->format("Y-m-d H:i:s");
                return $this->update($arrayDados, array('id' => $id));
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
