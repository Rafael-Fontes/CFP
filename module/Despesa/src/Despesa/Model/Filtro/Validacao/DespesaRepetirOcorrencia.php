<?php

/*
  Criado     : 01/12/2015
  Modificado : 01/12/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
    
    Se o campo repetir for 'S' o campo repetir_ocorrencia torna-se obrigatório.
    Se o campo repetir for 'N' o campo repetir_ocorrencia não deve ser informado.
    O campo repetir_ocorrencia deve ter um valor composto com tres letras.
 
 */

namespace Despesa\Model\Filtro\Validacao;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Digits;



class DespesaRepetirOcorrencia extends AbstractValidator
{
    
    const MSG_DESPESA_REPETIR_OCORRENCIA_REQUERIDA      = "msgDespesaRepetirOcorrenciaRequerida";
    const MSG_DESPESA_REPETIR_OCORRENCIA_NAO_INFORMAR   = "msgDespesaRepetirOcorrenciaNaoInformar";
    const MSG_DESPESA_REPETIR_OCORRENCIA_VALOR_INVALIDO = "msgDespesaRepetirOcorrenciaValorInvalido";
    
    
    
    protected $messageTemplates = array(
        self::MSG_DESPESA_REPETIR_OCORRENCIA_REQUERIDA      => "Campo obrigatório.",        
        self::MSG_DESPESA_REPETIR_OCORRENCIA_NAO_INFORMAR   => "Campo deve permanever vazio.",        
        self::MSG_DESPESA_REPETIR_OCORRENCIA_VALOR_INVALIDO => "O valor deve ser um numero inteiro.",        
    );
    
    
    
    
    
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    
    
    
    
    /*
     * @param  String  $valorCampo
     * @param  Array   $outrosCampos
     * @return Boolean
     */
    private function validarCampo($valorCampo, Array $outrosCampos = array())
    {
        if(isset($outrosCampos['repetir']) && $outrosCampos['repetir'] == 'S')
        {
            if(empty($valorCampo))
            {
                $this->error(self::MSG_DESPESA_REPETIR_OCORRENCIA_REQUERIDA);
                return false;
            }
        }
        elseif (isset($outrosCampos['repetir']) && $outrosCampos['repetir'] == 'N')
        {
            if(!empty($valorCampo))
            {
                $this->error(self::MSG_DESPESA_REPETIR_OCORRENCIA_NAO_INFORMAR);
                return false;
            }
        }
        
        if(!empty($valorCampo))
        {
            $digitos = new Digits();
            if(!$digitos->isValid($valorCampo))
            {
                $this->error(self::MSG_DESPESA_REPETIR_OCORRENCIA_VALOR_INVALIDO);
                return false;
            }
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
