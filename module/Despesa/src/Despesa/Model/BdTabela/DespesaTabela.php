<?php

/*
    Criado     : 22/11/2015
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
use Zend\Db\Sql\Expression;

use Despesa\Model\Despesa;

class DespesaTabela extends AbstractTableGateway
{

    protected $table = "despesas";


    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Despesa());
        $this->initialize();
    }





    /*
     * @param Boolean $paginado
     * @param Array   $filtro
     * @return Obj
    */
   public function buscarTodos($paginado = false, Array $filtro = array())
    {
        $sqlTotal = new Select();
        $sqlTotal->from('despesas')
                 ->columns(['total' => new Expression('SUM(valor)')])
                 ->join('clientes', 'despesas.fk_cliente = clientes.id', []);

        $sqlTotalPendente = new Select();
        $sqlTotalPendente->from('despesas')
                         ->columns(['totalPendente' => new Expression('SUM(valor)')])
                         ->join('clientes', 'despesas.fk_cliente = clientes.id', [])
                         ->where(['despesas.pagamento' => 'N']);

        $sqlTotalRecebido = new Select();
        $sqlTotalRecebido->from('despesas')
                         ->columns(['totalRecebido' => new Expression('SUM(valor)')])
                         ->join('clientes', 'despesas.fk_cliente = clientes.id', [])
                         ->where(['despesas.pagamento' => 'S']);


        $sqlPrincipal = new Select();
        $sqlPrincipal->from(['des' => 'despesas'])
                ->columns([
                    'id', 'fk_categoria', 'fk_subcategoria', 'fk_cliente', 'fk_conta', 'fk_cartao', 'descricao',
                    'valor', 'repetir', 'repetir_quando', 'repetir_ocorrencia', 'data_fatura',
                    'data_vencimento'=> new Expression('DATE_FORMAT(data_vencimento, "%d/%m/%Y")'),
                    'pagamento'      => new Expression('IF (pagamento = "S", "Efetivado", "Não Efetivado")'),
                    'pagamento_data' => new Expression('DATE_FORMAT(pagamento_data, "%d/%m/%Y")'),
                    'total'          => new Expression('?', [$sqlTotal]),
                    'totalPendente'  => new Expression('?', [$sqlTotalPendente]),
                    'totalRecebido'  => new Expression('?', [$sqlTotalRecebido])
                ])
                ->join(['cli' => 'clientes'], 'des.fk_cliente = cli.id', ['cli_nome' => 'nome'])
                ->join(['dca' => 'despesas_categorias'], 'des.fk_categoria = dca.id', ['dca_nome' => 'nome'])
                ->join(['con' => 'contas'], 'des.fk_conta = con.id', ['con_nome' => 'nome'])
                ->order('des.id Desc');

        //Retorna as receitas do mês corrente.
        if(!isset($filtro['data']) && !isset($filtro['data_inicio']) && !isset($filtro['data_fim']))
        {
            $parteSql = 'DATE_FORMAT(data_vencimento, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m")';

            $sqlTotalPendente->where($parteSql);
            $sqlTotalRecebido->where($parteSql);
            $sqlTotal->where($parteSql);
            $sqlPrincipal->where($parteSql);
        }

        //Filtros
        if (count($filtro) > 0)
        {
            $arrayFiltro = array_filter([
                'des.descricao' => (isset($filtro['descricao'])) ? filter_var($filtro['descricao'], FILTER_SANITIZE_STRING) . '%' : null,
                'des.pagamento' => (isset($filtro['pagamento'])) ? filter_var($filtro['pagamento'], FILTER_SANITIZE_STRING)       : null,
                'dca.nome'      => (isset($filtro['categoria'])) ? filter_var($filtro['categoria'], FILTER_SANITIZE_STRING) . '%' : null,
                'con.nome'      => (isset($filtro['conta']))     ? filter_var($filtro['conta'],     FILTER_SANITIZE_STRING) . '%' : null,
                'des.valor'     => (isset($filtro['valor']))     ? filter_var($filtro['valor'],     FILTER_SANITIZE_STRING)       : null,
            ]);

            foreach ($arrayFiltro as $chave => $valor)
            {
                $sqlPrincipal->where->like($chave, $valor);
            }

            if((isset($filtro['data_inicio']) && !empty('data_inicio')) &&
                isset($filtro['data_fim']) && !empty($filtro['data_fim']))
            {
                $dtInicio = filter_var($filtro['data_inicio'], FILTER_SANITIZE_NUMBER_INT);
                $dtFim    = filter_var($filtro['data_fim'],    FILTER_SANITIZE_NUMBER_INT);

                $dataInicio = implode('-', array_reverse(explode('-', $dtInicio)));
                $dataFim    = implode('-', array_reverse(explode('-', $dtFim)));

                $sqlTotalPendente->where->between('des.data_vencimento', $dataInicio, $dataFim);
                $sqlTotal->where->between('des.data_vencimento', $dataInicio, $dataFim);
                $sqlTotalRecebido->where->between('des.data_vencimento', $dataInicio, $dataFim);
                $sqlPrincipal->where->between('des.data_vencimento', $dataInicio, $dataFim);
            }

            //Retorna as receitas de acordo com o filtro data
            if(isset($filtro['data']) && !empty($filtro['data'])
               && !isset($filtro['data_inicio']) && !isset($filtro['data_fim']))
            {
                $data     = filter_var($filtro['data'], FILTER_SANITIZE_NUMBER_INT);
                $parteSql = ['DATE_FORMAT(data_vencimento, "%Y-%m") = DATE_FORMAT(?, "%Y-%m")' => $data];

                $sqlTotalPendente->where($parteSql);
                $sqlTotalRecebido->where($parteSql);
                $sqlTotal->where($parteSql);
                $sqlPrincipal->where($parteSql);
            }
        }

        if ($paginado)
        {
            $paginatorAdapter = new DbSelect(
                $sqlPrincipal,
                $this->getAdapter()
            );

            $paginado = new Paginator($paginatorAdapter);
            return $paginado;
        }

        $dados = $this->selectWith($sqlPrincipal);
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
        $select->from('despesas')
                ->columns([
                    'id', 'fk_categoria', 'fk_subcategoria', 'fk_cliente', 'fk_conta', 'fk_cartao', 'descricao',
                    'repetir', 'repetir_quando', 'repetir_ocorrencia', 'data_fatura', 'pagamento', 'grupo', 'anexo', 'obs',
                    'valor'           => new Expression('FORMAT(valor, 2, "de_DE")'),
                    'data_vencimento' => new Expression('DATE_FORMAT(data_vencimento, "%d/%m/%Y")'),
                    'pagamento_data'  => new Expression('DATE_FORMAT(pagamento_data, "%d/%m/%Y")')
                ])
                ->where(['id' => $id]);

        $dados = $this->selectWith($select);

        return $dados->current();
    }





    /*
     * @param  Despesa $objDespesa
     * @param  Array   $filtro
     * @return Array
     */
    public function buscarAssociados(Despesa $objDespesa, Array $filtro = [])
    {
        if(!empty($objDespesa->getGrupo()))
        {
            $select = new Select();
            $select->from('despesas')
                    ->columns(['id'])
                    ->where(['grupo' => $objDespesa->getGrupo()]);

            if(count($filtro) > 0)
            {
                if(isset($filtro['dataVen']) && $filtro['dataVen']){
                    $dataVencimento = implode('-', array_reverse(explode('/', $objDespesa->getDataVencimento())));
                    $select->where->greaterThanOrEqualTo('data_vencimento', $dataVencimento);
                }
            }

            $dados = $this->selectWith($select)->toArray();
            return array_column($dados, 'id');
        }

        return [];
    }





    /**
     *  Metodo  responsavel por salvar registros, quando necessario faz update
     * na tabela conta
     *
     * @param  Despesa\Model\Despesa $objDespesa
     * @return Boolean
     **/
    public function salvar(Despesa $objDespesa)
    {
        $arrayDados                    = array_filter($objDespesa->getArrayCopy());
        $arrayDados['valor']           = str_replace(['.', ','], ['', '.'], $objDespesa->getValor());
        $arrayDados['data_vencimento'] = $this->converterData($objDespesa->getDataVencimento());
        $arrayDados['pagamento_data']  = $this->converterData($objDespesa->getPagamentoData());
        $arrayDados['criado']          = $this->converterData(null, true);
        $arrayDados['modificado']      = $this->converterData(null, true);

        if(isset($arrayDados['repetir_ocorrencia']) && $arrayDados['repetir_ocorrencia'] > 1){
            $arrayDados['grupo'] = $this->gerarIdGrupo();
        }

        $conexao = $this->getAdapter()->getDriver()->getConnection();
        $conexao->beginTransaction();

        try
        {
            $fkConta  = (int) $objDespesa->getFkConta();
            $objConta = $this->getConta()->buscarUm($fkConta);
            if(!$objConta){
                return false;
            }

            $cont  = (int) (isset($arrayDados['repetir_ocorrencia'])) ? $arrayDados['repetir_ocorrencia'] : 1;
            for($i = 1; $i <= $cont; $i++)
            {
                if($cont > 1) {
                    $arrayDados['descricao'] = '';
                    $arrayDados['descricao'] = $i .'/'. $cont .' '. $objDespesa->getDescricao();
                }

                $commit = $this->insert($arrayDados);
                if(!$commit) {
                    break;
                }

                if($arrayDados['pagamento'] == 'S')
                {
                    $saldoConta = str_replace(['.', ','], ['', '.'], $objConta->getSaldo());
                    $novoSaldo  = $saldoConta - $arrayDados['valor'];

                    $objConta->setSaldo($novoSaldo);
                    $commit = $this->getConta()->salvar($objConta);

                    if(!$commit){
                        break;
                    }
                }

                $arrayDados['pagamento']       = 'N';
                $arrayDados['anexo']           = null;
                $arrayDados['data_vencimento'] = $this->gerarDataPagamento($arrayDados['data_vencimento'], $objDespesa->getRepetirQuando());
                $arrayDados['pagamento_data']  = null;
            }

            if(isset($commit) && $commit){
                $conexao->commit();
                return true;
            }

            $conexao->rollback();
            return false;
        }
        catch (Exception $exc)
        {
            $conexao->rollback();
        }
    }





    /**
     * Metodo  responsavel por editar registros
     *
     * @param  Despesa\Model\Despesa $objDespesaForm
     * @return Boolean
     **/
    public function editar(Despesa $objDespesaForm)
    {
        $arrayDados                    = array_filter($objDespesaForm->getArrayCopy());
        $arrayDados['valor']           = str_replace(['.', ','], ['', '.'], $objDespesaForm->getValor());
        $arrayDados['data_vencimento'] = $this->converterData($objDespesaForm->getDataVencimento());
        $arrayDados['pagamento_data']  = $this->converterData($objDespesaForm->getPagamentoData());
        $arrayDados['modificado']      = $this->converterData(null, true);

        $despesaFormId = (int) $objDespesaForm->getId();
        $objDespesaBd  = $this->buscarUm($despesaFormId);
        if(!$objDespesaBd){
            return false;
        }

        $conexao = $this->getAdapter()->getDriver()->getConnection();
        $conexao->beginTransaction();

        try
        {
            $commit = $this->update($arrayDados, ['id' => $despesaFormId]);
            if(!$commit){
                $conexao->rollback();
                return false;
            }

            $objContaBd   = $this->getConta()->buscarUm($objDespesaBd->getFkConta());
            $saldoContaBd = str_replace(['.', ','], ['', '.'], $objContaBd->getSaldo());
            $saldoDespeBd = str_replace(['.', ','], ['', '.'], $objDespesaBd->getValor());

            //Se o pagamento estiver como efetuado
            if($objDespesaForm->getPagamento() == 'S')
            {
                //Se a conta foi alterada
                if($objDespesaForm->getFkConta() != $objDespesaBd->getFkConta())
                {
                    $novoSaldocontaBd = $saldoContaBd + $saldoDespeBd;
                    $objContaBd->setSaldo($novoSaldocontaBd);
                    $commit = $this->getConta()->salvar($objContaBd);

                    if($commit)
                    {
                        $objContaNova       = $this->getConta()->buscarUm($objDespesaForm->getFkConta());
                        $saldoContaNova     = str_replace(['.', ','], ['', '.'], $objContaNova->getSaldo());
                        $novoSaldocontaNova = $saldoContaNova - $arrayDados['valor'];

                        $objContaNova->setSaldo($novoSaldocontaNova);
                        $commit = $this->getConta()->salvar($objContaNova);
                    }
                }
                //Se a conta não foi alerada mais o valor foi alterado
                elseif($objDespesaForm->getValor() != $objDespesaBd->getValor())
                {
                    $novoSaldo = ($saldoContaBd + $saldoDespeBd) - $arrayDados['valor'];
                    $objContaBd->setSaldo($novoSaldo);
                    $commit = $this->getConta()->salvar($objContaBd);

                }
                elseif($objDespesaForm->getPagamento() != $objDespesaBd->getPagamento())
                {
                    $novoSaldo = $saldoContaBd - $saldoDespeBd;
                    $objContaBd->setSaldo($novoSaldo);
                    $commit = $this->getConta()->salvar($objContaBd);
                }
            }
            else{
                if($objDespesaForm->getPagamento() != $objDespesaBd->getPagamento())
                {
                    $novoSaldo = $saldoContaBd + $arrayDados['valor'];
                    $objContaBd->setSaldo($novoSaldo);

                    $commit = $this->getConta()->salvar($objContaBd);
                }
            }


            if(isset($commit) && $commit)
            {
                if(isset($arrayDados['anexo']) && !empty($arrayDados['anexo'])) 
                {
                    $this->deletarArquivo($objDespesaBd);
                }
                
                $conexao->commit();
                return true;
            }

            $conexao->rollback();
            return false;
        }
        catch (Exception $exc)
        {
            $conexao->rollback();
        }
    }





    /**
     * Deleta registro e quando necessario faz update na tabela conta.
     *
     * @param  Array    $id
     * @return Boolean
    **/
    public function deletar($arrayId = array())
    {
        $conexao = $this->getAdapter()->getDriver()->getConnection();
        $conexao->beginTransaction();

        try
        {
            foreach ($arrayId as $id)
            {
                $id = (int) $id;

                $objDespesaBd = $this->buscarUm($id);
                if(!$objDespesaBd){
                    $commit = false;
                    break;
                }

                $commit = $this->delete(['id' => $id]);
                if(!$commit){
                    break;
                }

                if($objDespesaBd->getPagamento() == 'S')
                {
                    $objConta     = $this->getConta()->buscarUm($objDespesaBd->getFkConta());
                    $saldoConta   = str_replace(['.', ','], ['', '.'], $objConta->getSaldo());
                    $saldoDespesa = str_replace(['.', ','], ['', '.'], $objDespesaBd->getValor());
                    $novoSaldo    = $saldoConta + $saldoDespesa;

                    $objConta->setSaldo($novoSaldo);
                    $retorno = $this->getConta()->salvar($objConta);

                    $commit       = true;
                    $arrayAnexo[] = $objDespesaBd->getAnexo();

                    if(!$retorno){
                        $commit = false;
                        break;
                    }
                }
            }

            if(isset($commit) && $commit){
                foreach($arrayAnexo as $anexo){
                    $this->deletarArquivo($anexo);
                }

                $conexao->commit();
                return true;
            }

            $conexao->rollback();
            return false;
        }
        catch (Exception $exc)
        {
            $conexao->rollback();
        }
    }





    /*
     * Deletar um arquivo
     * @param String $anexo
     */
    private function deletarArquivo($anexo)
    {
        if(!is_null($anexo) && !empty($anexo)){
            if (file_exists($anexo)){
                unlink($anexo);
            }
        }
    }





    /*
     * @param  Date $dataVencimento
     * @return Date|Boolean
     */
    private function gerarDataPagamento($dataVencimento = null, $quando = null)
    {
        if(is_null($dataVencimento) || empty($dataVencimento)){
            return false;
        }

        if(is_null($quando) || empty($quando)){
            return false;
        }

        switch ($quando)
        {
            case 'DIA': $quando = 'day';   break;
            case 'SEM': $quando = 'week';  break;
            case 'MES': $quando = 'month'; break;
            case 'ANO': $quando = 'year';  break;
            default:  return false;
        }

        $data = new \DateTime($dataVencimento);
        return $data->modify("+1 {$quando}")->format('Y-m-d');
    }





    /*
     * Gera um valor unico para o campo grupo
     * @return String
     */
    private function gerarIdGrupo()
    {
        while (true)
        {
            $grupoId = uniqid(rand(), true);
            $dados   = $this->select(['grupo' => $grupoId]);

            if($dados->count() == 0){
                return $grupoId;
            }
        }
    }





    /*
     * O parametro data deve ser passodo no formato d/m/Y, se uma data não
     * for informada, retorna a data atual.
     *
     * @param  Date    $data      d/m/Y
     * @param  String  $dataAtual
     * @return String  $formato
     */
    private function converterData($data = null, $dataAtual = null)
    {
        if(!is_null($data) && !empty($data)) {
            return implode('-', array_reverse(explode('/', $data)));
        }

        if($dataAtual){
            return (new \DateTime('NOW', new \DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s');
        }
    }





    /*
     * Isso é uma dependencia, deve ser resolvido
     */
    private function getConta()
    {
        return new \Conta\Model\BdTabela\ContaTabela($this->adapter);
    }
}
