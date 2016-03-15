<?php

namespace Cliente\Model\Filtro;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class AuthFiltro implements InputFilterAwareInterface{
    
    
    
    protected $inputFilter;
    protected $dbAdapter;

    
    
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }
    
    
    
    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            
            $inputFilter = new InputFilter();
            
            
            
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
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
                                'isEmpty' => 'Campo obrigatório'
                            ),
                        ),
                    ),                    
                    array(
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options' => array(                            
                            'messages'  => array(
                                'emailAddressInvalid'         => 'Formato de email inválido',
                                'emailAddressInvalidFormat'   => 'Não é um endereço de email válido',
                                'emailAddressLengthExceeded'  => 'Email excede o tamanho permitido',
                                'emailAddressInvalidHostname' => "'%hostname%' Não é um nome de host válido para o endereço de e-mail",
                            ),
                        ),
                    ),                    
                ),
            ));
            
            
            
            $inputFilter->add(array(
                'name' => 'senha',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
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
                ),
            ));
            
    
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;         
    }
}