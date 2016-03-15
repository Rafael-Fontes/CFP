<?php

/*
  Criado     : 22/09/2015
  Modificado : 28/09/2015
  Autor      :
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
 
        Responsavel por todas as validações e regras de negócio
 */

namespace Conta\Model\Filtro;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class TipoFiltro implements InputFilterAwareInterface
{
    
    
    protected $inputFilter;
    protected $dbAdapter;
    
    
    public function __construct(Adapter $dbAdapeter) {
        $this->dbAdapter = $dbAdapeter;
    }
    
    
    
    
    
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }
    
    
    
    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
                        
            
            
            $inputFilter->add(array(
                'name' => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'htmlentities'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Campo obrigatório!'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 40,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no maximo %max% caractere'
                            )
                        ),
                    ),                    
                ),
            ));

            
            
            $inputFilter->add(array(
                'name' => 'situacao',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'Htmlentities'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Campo obrigatório!'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 1,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no maximo %max% caractere'
                            )
                        ),
                    ),   
                ),
            ));
                       
                                              

            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;         
    }  
}