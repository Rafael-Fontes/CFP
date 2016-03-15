<?php

/*
  Criado     : 23/09/2015
  Modificado : 23/09/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
 * 
        Responsavel por motar o formulário
 */


namespace Cliente\Form;

use Zend\Form\Form;


class AuthForm extends Form{
    
    
    public function __construct($name = null) {
        
        parent::__construct('autenticacao');
        
        
        $this->setAttributes(array(
            'method'    => 'POST',
            'class'     => 'form-horizontal',
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => '',
                'placeholder'   => 'Digite seu email',
            ),
        ));
        
        
        
        $this->add(array(
            'name' => 'senha',
            'type' => 'Password',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'placeholder'   => 'Digite sua senha',
            ),
        ));        
        
        
        $this->add(array(
            'name' => 'lembrar',
            'type' => 'Checkbox',
            'options' => array(
                'use_hidden_element' => true,
                'checked_value'      => 'sim',
                'unchecked_value'    => 'nao'
            ),
        ));

        
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                    'csrf_options' => array(
                            'timeout' => 600
                    )
            )
        ));
    }
}
