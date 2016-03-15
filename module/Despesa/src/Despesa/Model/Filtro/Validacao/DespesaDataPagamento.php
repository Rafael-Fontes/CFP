<?php

/*
  Criado     : 22/10/2015
  Modificado : 23/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Se o pagamento da despesa já tiver sido efetuado, a data do pagamento se
     torna obrigatoria.
 */

namespace Despesa\Model\Filtro\Validacao;

use Zend\Validator\AbstractValidator;


class DespesaDataPagamento extends AbstractValidator
{

    const MSG_DATA_PAGAMENTO_REQUERIDA     = "msgDataPagamentoRequerida";
    const MSG_DATA_PAGAMENTO_NAO_REQUERIDA = "msgDataPagamentoNaoRequerida";
    const MSG_DATA_PAGAMENTO_INVALIDA      = "msgDataPagamentoInvalida";


    protected $messageTemplates = array(
        self::MSG_DATA_PAGAMENTO_REQUERIDA     => "É necessaroi informar a data do pagamento!",
        self::MSG_DATA_PAGAMENTO_NAO_REQUERIDA => "Data do pagamento não deve ser informada!",
        self::MSG_DATA_PAGAMENTO_INVALIDA      => "Data inválida!!!!"
    );




    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    

    
    
    /*
     * Verifica se o valor informado é uma data valida.
     * 
     * @param  Date $data d/m/Y
     * @return Boolean
     */
    private function checarData($data)
    {
        if (strlen(trim($data)) == 10)
        {
            $arrayData = explode('/', $data);
            if (checkdate($arrayData[1], $arrayData[0], $arrayData[2]))
            {
                return true;
            }
        }

        $this->error(self::MSG_DATA_PAGAMENTO_INVALIDA);
        return false;
    }

    
    
    
    
    /*
     * Se o valor do campo pamgamento for "S" o campo data_pagamento é obrigatório,
     * se o valor do campo pagamento for "N" o campo data_pagamento deve ser vazio
     * 
     * @param  String  $valorCampo
     * @param  Array   $outrosCampos
     * @return Boolean
     */
    private function validarCampo($valorCampo, Array $outrosCampos = array())
    {
        if (isset($outrosCampos['pagamento']) && $outrosCampos['pagamento'] == 'S')
        {
            if (empty($valorCampo))
            {
                $this->error(self::MSG_DATA_PAGAMENTO_REQUERIDA);
                return false;
            }
        } elseif (isset($outrosCampos['pagamento']) && $outrosCampos['pagamento'] == 'N')
        {
            if (!empty($valorCampo))
            {
                $this->error(self::MSG_DATA_PAGAMENTO_NAO_REQUERIDA);
                return false;
            }
        }

        if (!empty($valorCampo))
        {
            return $this->checarData($valorCampo);
        }

        return true;
    }

    
    
    
    
    /*
     * @param  String $value
     * @param  Array  $context
     * @return Boolean
     */
    public function isValid($value, Array $context = array())
    {
       $this->setValue($value);
       
       return $this->validarCampo($value, $context);
    }

}
