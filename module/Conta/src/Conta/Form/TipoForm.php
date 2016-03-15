<?php

/*
  Criado     : 28/09/2015
  Modificado : 28/09/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
 
        Responsavel por montar os campos do formulário
 */


namespace Conta\Form;

use Zend\Form\Form;


class TipoForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('tipos');
        
        
        $this->setAttributes(array(
            'method'    => 'POST',
            'class'     => 'form-horizontal',
        ));
        
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
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
                'placeholder'   => 'Digite seu nome',
            ),
        ));
        
        
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'situacao',
            'options' => array(
                'label' => '',
                'value_options' => array(
                    'A' => 'Ativo',
                    'I' => 'Inativo'
                ),
            ),
            'attributes' => array(
                'value'            => 'A',
                'class'            => 'form-control select',
                'data-live-search' => "true"
            )
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
