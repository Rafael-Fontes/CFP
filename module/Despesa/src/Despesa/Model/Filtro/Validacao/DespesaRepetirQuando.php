<?php

/*
  Criado     : 01/12/2015
  Modificado : 01/12/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

       ...
 */

namespace Despesa\Model\Filtro\Validacao;

use Zend\Validator\AbstractValidator;
use Zend\Validator\StringLength;



class DespesaRepetirQuando extends AbstractValidator
{
    
    const MSG_DESPESA_REPETIR_QUANDO_REQUERIDA      = "msgDespesaRepetirQuandoRequerida";
    const MSG_DESPESA_REPETIR_QUANDO_NAO_INFORMAR   = "msgDespesaRepetirQuandoNaoInformar";
    const MSG_DESPESA_REPETIR_QUANDO_VALOR_INVALIDO = "msgDespesaRepetirQuandoValorInvalido";
    
    
    
    protected $messageTemplates = array(
        self::MSG_DESPESA_REPETIR_QUANDO_REQUERIDA      => "Campo obrigatório.",        
        self::MSG_DESPESA_REPETIR_QUANDO_NAO_INFORMAR   => "Campo deve permanever vazio.",        
        self::MSG_DESPESA_REPETIR_QUANDO_VALOR_INVALIDO => "Valor informado é diferente do esperado.",        
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
        if (isset($outrosCampos['repetir']) && $outrosCampos['repetir'] == 'S')
        {
            if (empty($valorCampo))
            {
                $this->error(self::MSG_DESPESA_REPETIR_QUANDO_REQUERIDA);
                return false;
            }
        } 
        elseif (isset($outrosCampos['repetir']) && $outrosCampos['repetir'] == 'N')
        {
            if (!empty($valorCampo))
            {
                $this->error(self::MSG_DESPESA_REPETIR_QUANDO_NAO_INFORMAR);
                return false;
            }
        }

        if(!empty($valorCampo))
        {
            $stringLenght = new StringLength();
            $stringLenght->setMin('3');
            $stringLenght->setMax('3');
            $stringLenght->setEncoding("UTF-8");
            
            if(!$stringLenght->isValid($valorCampo))
            {
                $this->error(self::MSG_DESPESA_REPETIR_QUANDO_VALOR_INVALIDO);
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
