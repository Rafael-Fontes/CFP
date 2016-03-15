<?php

/*
  Criado     : 08/10/2015
  Modificado : 08/10/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
 
        Responsavel por montar os campos do formulário
 */

namespace Receita\Form;


use Zend\Form\Form;


class ReceitaSubcategoriaForm extends Form
{
    
    public function __construct($name = null)
    {
        parent::__construct('receitaSubcategoria');
        
        
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
            'name' => 'fk_categoria',
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
                'placeholder'   => 'Digite o nome da subcategoria',
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
