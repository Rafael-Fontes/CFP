<?php

/*
  Criado     : 18/09/2015
  Modificado : 17/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
 
        Responsavel por montar os campos do formulário
 */

namespace Conta\Form;


use Zend\Form\Form;


class ContaForm extends Form
{
    
    public function __construct($name = null)
    {
        parent::__construct('contas');
        
        
        $this->setAttributes(array(
            'method'    => 'POST',
            'class'     => 'form-horizontal',
        ));
        
        
        $this->add(array(
            'type' => 'Hidden',
            'name' => 'id',            
        ));
        
        
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'fk_tipo',
            'options' => array(
                'label'         => '',
                'empty_option'  => 'Selecione uma opção',
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'class'            => 'form-control select',
                'data-live-search' => "true"
            )
        ));
        
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'fk_cliente',
            'options' => array(
                'label'        => '',
                'empty_option' => 'Selecione uma opção',
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class'            => 'form-control select',
                'data-live-search' => "true"
            )
        ));
        
        
        $this->add(array(
            'name' => 'nome',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => '',
                'placeholder'   => 'Digite o nome da conta',
            ),
        ));
        
        
              
        $this->add(array(
            'name' => 'saldo',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'data-mascara'  => 'dinheiro-br1',
                'placeholder'   => 'Digite seu saldo',               
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
