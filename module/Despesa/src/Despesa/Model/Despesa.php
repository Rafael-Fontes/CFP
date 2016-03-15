<?php

namespace Despesa\Model;

use Zend\Stdlib\Hydrator\ClassMethods;

/*
  Criado     : 22/11/2015
  Modificado : 22/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
  ...
 */

class Despesa
{

    private $id;
    private $fk_categoria;
    private $fk_subcategoria;
    private $fk_cliente;
    private $fk_conta;
    private $fk_cartao;
    private $descricao;
    private $data_vencimento;
    private $valor;
    private $repetir;
    private $repetir_quando;
    private $repetir_ocorrencia;
    private $data_fatura;
    private $pagamento;
    private $pagamento_data;
    private $pagamento_desconto;
    private $pagamento_juro;
    private $pagamento_valor;
    private $grupo;
    private $anexo;
    private $obs;
    private $criado;
    private $modificado;

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

    public function getFkSubcategoria()
    {
        return $this->fk_subcategoria;
    }

    public function setFkSubcategoria($fkSubcategoria)
    {
        $this->fk_subcategoria = $fkSubcategoria;
        return $this;
    }

    public function getFkCliente()
    {
        return $this->fk_cliente;
    }

    public function setFkCliente($fkCliente)
    {
        $this->fk_cliente = $fkCliente;
        return $this;
    }

    public function getFkConta()
    {
        return $this->fk_conta;
    }

    public function setFkConta($fkConta)
    {
        $this->fk_conta = $fkConta;
        return $this;
    }

    public function getFkCartao()
    {
        return $this->fk_cartao;
    }

    public function setFkCartao($fkCartao)
    {
        $this->fk_cartao = $fkCartao;
        return $this;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getDataVencimento()
    {
        return $this->data_vencimento;
    }

    public function setDataVencimento($dataVencimento)
    {
        $this->data_vencimento = $dataVencimento;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function getRepetir()
    {
        return $this->repetir;
    }

    public function setRepetir($repetir)
    {
        $this->repetir = $repetir;
        return $this;
    }

    public function getRepetirQuando()
    {
        return $this->repetir_quando;
    }

    public function setRepetirQuando($repetirQuando)
    {
        $this->repetir_quando = $repetirQuando;
        return $this;
    }

    public function getRepetirOcorrencia()
    {
        return $this->repetir_ocorrencia;
    }

    public function setRepetirOcorrencia($repetirOcorrencia)
    {
        $this->repetir_ocorrencia = $repetirOcorrencia;
        return $this;
    }

    public function getDataFatura()
    {
        return $this->data_fatura;
    }

    public function setDataFatura($dataFatura)
    {
        $this->data_fatura = $dataFatura;
        return $this;
    }

    public function getPagamento()
    {
        return $this->pagamento;
    }

    public function setPagamento($pagamento)
    {
        $this->pagamento = $pagamento;
        return $this;
    }

    public function getPagamentoData()
    {
        return $this->pagamento_data;
    }

    public function setPagamentoData($pagamentoData)
    {
        $this->pagamento_data = $pagamentoData;
        return $this;
    }

    public function getPagamentoDesconto()
    {
        return $this->pagamento_desconto;
    }

    public function setPagamentoDesconto($pagamentoDesconto)
    {
        $this->pagamento_desconto = $pagamentoDesconto;
        return $this;
    }

    public function getPagamentoJuro()
    {
        return $this->pagamento_juro;
    }

    public function setPagamentoJuro($pagamentoJuro)
    {
        $this->pagamento_juro = $pagamentoJuro;
        return $this;
    }

    public function getPagamentoValor()
    {
        return $this->pagamento_valor;
    }

    public function setPagamentoValor($pagamentoValor)
    {
        $this->pagamento_valor = $pagamentoValor;
        return $this;
    }

    public function getGrupo()
    {
        return $this->grupo;
    }

    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
        return $this;
    }

    public function getAnexo()
    {
        return $this->anexo;
    }

    public function setAnexo($anexo)
    {
        if (is_array($anexo))
        {
            if (empty($anexo['name']))
            {
                $this->anexo = null;
                return $this;
            } else
            {
                $this->anexo = str_replace('\\', '/', $anexo['tmp_name']);
                return $this;
            }
        }

        $this->anexo = $anexo;
        return $this;
    }

    public function getObs()
    {
        return $this->obs;
    }

    public function setObs($obs)
    {
        $this->obs = $obs;
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
