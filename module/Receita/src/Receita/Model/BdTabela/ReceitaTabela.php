<?php

/*
    Criado     : 09/10/2015
    Modificado : 13/11/2015
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
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;

use Receita\Model\Receita;


class ReceitaTabela extends AbstractTableGateway
{

    protected $table = "receitas";


    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Receita());
        $this->initialize();
    }





    /* Retorna todos os dados paginados ou não, aceita um array de filtros
     *
     * @param  Boolean $paginado
     * @param  Array   $filtro
     * @return Obj|Null
    */
    public function buscarTodos($paginado = false, Array $filtro = array())
    {
        $sqlTotal = new Select();
        $sqlTotal->from('receitas')
                 ->columns(['total' => new Expression('SUM(receitas.valor)')])
                 ->join('clientes', 'receitas.fk_cliente = clientes.id', []);

        $sqlTotalPendente = new Select();
        $sqlTotalPendente->from('receitas')
                         ->columns(['total_pendente' => new Expression('SUM(receitas.valor)')])
                         ->join('clientes', 'receitas.fk_cliente = clientes.id', [])
                         ->where(['receitas.pagamento' => 'N',]);

        $sqlTotalRecebido = new Select();
        $sqlTotalRecebido->from('receitas')
                         ->columns(['total' => new Expression('SUM(receitas.valor)')])
                         ->join('clientes', 'receitas.fk_cliente = clientes.id', [])
                         ->where(['receitas.pagamento' => 'S']);

        $sqlPrincipal = new Select();
        $sqlPrincipal->from(array('rec' => 'receitas'))
                     ->columns(array(
                        'id', 'descricao',
                        'valor',
                        'pagamento'      => new Expression('IF (pagamento = "S", "Efetivado", "Não Efetivado")'),
                        'pagamento_data' => new Expression("DATE_FORMAT(pagamento_data, '%d/%m/%Y')"),
                        'total'          => new Expression('?', array($sqlTotal)),
                        'total_pendente' => new Expression('?', array($sqlTotalPendente)),
                        'total_recebido' => new Expression('?', array($sqlTotalRecebido))
                     ))
                     ->join(array('cli' => 'clientes'), 'rec.fk_cliente = cli.id', array('cli_nome' => 'nome'))
                     ->join(array('con' => 'contas'),   'rec.fk_conta = con.id',   array('con_nome' => 'nome'))
                     ->join(array('cat' => 'receitas_categorias'), 'rec.fk_categoria = cat.id', array('cat_nome' => 'nome'))
                     ->order('rec.id Desc');


        //Retorna as receitas do mês corrente.
        if(!isset($filtro['data']) && !isset($filtro['data_inicio']) && !isset($filtro['data_fim']))
        {
            $parteSql = 'DATE_FORMAT(data_vencimento, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m")';

            $sqlTotalPendente->where($parteSql);
            $sqlTotalRecebido->where($parteSql);
            $sqlTotal->where($parteSql);
            $sqlPrincipal->where($parteSql);
        }


        //Outros filtros
        if(count($filtro) > 0)
        {
            $arrayFiltro = array(
                'rec.descricao'      => (isset($filtro['descricao'])) ? filter_var($filtro['descricao'], FILTER_SANITIZE_STRING) .'%' : null,
                'cat.nome'           => (isset($filtro['categoria'])) ? filter_var($filtro['categoria'], FILTER_SANITIZE_STRING) .'%' : null,
                'con.nome'           => (isset($filtro['conta']))     ? filter_var($filtro['conta'],     FILTER_SANITIZE_STRING) .'%' : null,
                'rec.pagamento'      => (isset($filtro['pagamento'])) ? filter_var($filtro['pagamento'], FILTER_SANITIZE_STRING)      : null,
                'rec.valor'          => (isset($filtro['valor']))     ? filter_var($filtro['valor'],     FILTER_SANITIZE_STRING)      : null,
            );

            foreach (array_filter($arrayFiltro) as $chave => $valor)
            {
                $sqlPrincipal->where->like($chave, $valor);
            }

            if((isset($filtro['data_inicio']) && !empty('data_inicio')) &&
                isset($filtro['data_fim']) && !empty($filtro['data_fim']))
            {
                $data_inicio = $this->converterData(filter_var($filtro['data_inicio'], FILTER_SANITIZE_NUMBER_INT));
                $data_fim    = $this->converterData(filter_var($filtro['data_fim'],    FILTER_SANITIZE_NUMBER_INT));

                $sqlTotalPendente->where->between('rec.data_vencimento', $data_inicio, $data_fim);
                $sqlTotal->where->between('rec.data_vencimento', $data_inicio, $data_fim);
                $sqlTotalRecebido->where->between('rec.data_vencimento', $data_inicio, $data_fim);
                $sqlPrincipal->where->between('rec.data_vencimento', $data_inicio, $data_fim);
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


        //Se os dados forem paginados, entra aqui.
        if($paginado)
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
        $id = (int) $id;

        $select = new Select();
        $select->from('receitas')
                ->columns(array(
                    'id', 'fk_categoria', 'fk_subcategoria', 'fk_cliente', 'fk_conta',
                    'descricao', 'repetir', 'repetir_quando', 'repetir_ocorrencia',
                    'pagamento', 'pagamento_desconto', 'pagamento_juro', 'pagamento_valor',
                    'grupo', 'anexo', 'obs', 'criado', 'modificado',
                    'valor'           => new Expression("FORMAT(valor, 2, 'de_DE')"),
                    'data_vencimento' => new Expression("DATE_FORMAT(data_vencimento, '%d/%m/%Y')"),
                    'pagamento_data'  => new Expression("DATE_FORMAT(pagamento_data, '%d/%m/%Y')"),
                ))
                ->where(array('id' => $id));

        $dados = $this->selectWith($select);

        return $dados->current();
    }

    
    
    

    /*
     * @param  Despesa $objReceita
     * @param  Array   $filtro
     * @return Array
     */
    public function buscarAssociados(Receita $objReceita, Array $filtro = [])
    {
        if(!empty($objReceita->getGrupo()))
        {
            $select = new Select();
            $select->from('receitas')
                    ->columns(['id'])
                    ->where(['grupo' => $objReceita->getGrupo()]);

            if(count($filtro) > 0)
            {
                if(isset($filtro['dataVen']) && $filtro['dataVen']){
                    $dataVencimento = implode('-', array_reverse(explode('/', $objReceita->getDataVencimento())));
                    $select->where->greaterThanOrEqualTo('data_vencimento', $dataVencimento);
                }
            }

            $dados = $this->selectWith($select)->toArray();
            return array_column($dados, 'id');
        }

        return [];
    }




    
    /*
     * Metodo  responsavel por salvar registros
     * @param  Receita\Model\Receita $receita
     * @return Boolean
    */
    public function salvar(Receita $objReceita)
    {
        $arrayDados                    = array_filter($objReceita->getArrayCopy());
        $arrayDados['valor']           = str_replace(['.', ','], ['', '.'], $objReceita->getValor());
        $arrayDados['data_vencimento'] = $this->converterData($objReceita->getDataVencimento());
        $arrayDados['pagamento_data']  = $this->converterData($objReceita->getPagamentoData());
        $arrayDados['criado']          = $this->converterData(null, true);
        $arrayDados['modificado']      = $this->converterData(null, true);

        if(isset($arrayDados['repetir_ocorrencia']) && $arrayDados['repetir_ocorrencia'] > 1){
            $arrayDados['grupo'] = $this->gerarIdGrupo();
        }

        $conexao = $this->adapter->getDriver()->getConnection();
        $conexao->beginTransaction();

        try
        {
            $fkConta  = (int) $objReceita->getFkConta();
            $objConta = $this->getConta()->buscarUm($fkConta);
            if(!$objConta){
                return false;
            }

            $cont  = (int) (isset($arrayDados['repetir_ocorrencia'])) ? $arrayDados['repetir_ocorrencia'] : 1;
            for($i = 1; $i <= $cont; $i++)
            {
                if($cont > 1) {
                    $arrayDados['descricao'] = '';
                    $arrayDados['descricao'] = $i .'/'. $cont .' '. $objReceita->getDescricao();
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
                $arrayDados['data_vencimento'] = $this->gerarDataPagamento($arrayDados['data_vencimento'], $objReceita->getRepetirQuando());
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




    /*
     * Metodo  responsavel por pelo update dos registros
     * @param  Receita\Model\Receita $receita
     * @return Boolean
     */
    public function editar(Receita $receita)
    {
        $dados = array_filter($receita->getArrayCopy());
        $id    = (int) $receita->getId();

        $objReceitaBd = $this->buscarUm($id);
        if($objReceitaBd)
        {
            $dados['valor']           = str_replace(['.', ','], ['', '.'], $dados['valor']);
            $dados['data_vencimento'] = $this->converterData($receita->getDataVencimento());
            $dados['pagamento_data']  = (!empty($receita->getPagamentoData()))? $this->converterData($receita->getPagamentoData()) : null;
            $dados['modificado']      = $this->converterData();

            $conecao = $this->adapter->getDriver()->getConnection();
            $conecao->beginTransaction();

            try
            {
                $retorno = $this->update($dados, ['id' => $id]);
                if(!$retorno){
                    $conecao->rollback();
                    return false;
                }

                $objContaAtual     = $this->getConta()->buscarUm($objReceitaBd->getFkConta());
                $contaAtualSaldo   = str_replace(['.', ','], ['', '.'], $objContaAtual->getSaldo());
                $receitaAtualSaldo = str_replace(['.', ','], ['', '.'], $objReceitaBd->getValor());

                if($receita->getFkConta() != $objReceitaBd->getFkConta())
                {
                    $saldoContaAtual = $contaAtualSaldo - $receitaAtualSaldo;
                    $objContaAtual->setSaldo($saldoContaAtual);
                    $retornoContaAtual = $this->getConta()->salvar($objContaAtual);

                    if($retornoContaAtual)
                    {
                       $objContaNova   = $this->getConta()->buscarUm($receita->getFkConta());
                       $contaNovaSaldo = str_replace(['.', ','], ['', '.'], $objContaNova->getSaldo());
                       $saldoContaNova = $contaNovaSaldo + $dados['valor'];

                       $objContaNova->setSaldo($saldoContaNova);
                       $retornoContaNova = $this->getConta()->salvar($objContaNova);

                       if($retornoContaNova){
                           $conecao->commit ();
                           return true;
                       }
                    }

                    $conecao->rollback();
                    return false;
                }
                elseif($receita->getValor() != $objReceitaBd->getValor())
                {
                    $novoSaldo = ($contaAtualSaldo - $receitaAtualSaldo) + $dados['valor'];
                    $objContaAtual->setSaldo($novoSaldo);
                    $retornoContaAtual = $this->getConta()->salvar($objContaAtual);

                    if($retornoContaAtual){
                        $conecao->commit();
                        return true;
                    }

                    $conecao->rollback();
                    return false;
                }

                $conecao->commit();
                return true;
            }
            catch (Exception $exc) {
                $conecao->rollback();
            }
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
                
                $objReceitaBd = $this->buscarUm($id);
                if(!$objReceitaBd){
                    $commit = false;
                    break;
                }

                $commit = $this->delete(['id' => $id]);
                if(!$commit){
                    break;
                }                
                
                if($objReceitaBd->getPagamento() == 'S')
                {
                    $objConta     = $this->getConta()->buscarUm($objReceitaBd->getFkConta());
                    $saldoConta   = str_replace(['.', ','], ['', '.'], $objConta->getSaldo());
                    $saldoDespesa = str_replace(['.', ','], ['', '.'], $objReceitaBd->getValor());
                    $novoSaldo    = $saldoConta + $saldoDespesa;

                    $objConta->setSaldo($novoSaldo);
                    $commit = $this->getConta()->salvar($objConta);

                    if(!$commit){
                        break;
                    }
                }
                
                $arrayAnexo[] = $objReceitaBd->getAnexo();
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
     * O parametro data deve ser passodo no formato d/m/Y, se uma data não
     * for informada, retorna a data atual.
     *
     * @param  Date    $data      d/m/Y
     * @param  Boolena $dataAtual
     * @return String  Y-m-d H:i:s
     */
    private function converterData($data = null, $dataAtual = true)
    {
        if(!is_null($data) && !empty($data)) {
            $nData = implode('-', array_reverse(explode('/', $data)));
        }

        if($dataAtual){
            $nData = "NOW";
        }

        if(!empty($nData)){
            return (new \DateTime($nData, new \DateTimeZone('America/Sao_Paulo')))->format("Y-m-d H:i:s");
        }

        return;
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
     * Isso é uma dependencia, deve ser resolvido
     */
    private function getConta()
    {
        return new \Conta\Model\BdTabela\ContaTabela($this->adapter);
    }


}