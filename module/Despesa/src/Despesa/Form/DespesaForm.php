<?php

/*
  Criado     : 23/11/2015
  Modificado : 23/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel por montar os campos do formulário
 */

namespace Despesa\Form;


use Zend\Form\Form;


class DespesaForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('despesa');


        $this->setAttributes(array(
            'method'    => 'POST',
            'enctype'   => 'multipart/form-data',
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
            'type' => 'Select',
            'name' => 'fk_subcategoria',
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
            'type' => 'Select',
            'name' => 'fk_conta',
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
            'name' => 'descricao',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => '',
                'placeholder'   => 'Digite o nome da despesa',
            ),
        ));


        $this->add(array(
            'name' => 'data_vencimento',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'            => 'form-control',
                'placeholder'      => 'Data do vencimento',
                'data-calendario'  => 'data-br1',

            ),
        ));


        $this->add(array(
            'name' => 'valor',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => '',
                'placeholder'   => 'Digite o valor',
                'data-mascara'  => 'dinheiro-br1',
            ),
        ));


        $this->add(array(
            'type' => 'Select',
            'name' => 'pagamento',
            'options' => array(
                'label' => '',
                'disable_inarray_validator' => true,
                'empty_option' => 'Selecione uma opção',
                'value_options' => array(
                    'S' => 'Efetuado',
                    'N' => 'Pendente'
                ),
            ),
            'attributes' => array(
                'value'            => '',
                'class'            => 'form-control select',
                'data-live-search' => "true"
            )
        ));


        $this->add(array(
            'name' => 'pagamento_data',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'            => 'form-control',
                'placeholder'      => 'Data do pagamento',
                'data-calendario'  => 'data-br1',
            ),
        ));


        $this->add(array(
            'type' => 'Select',
            'name' => 'repetir',
            'options' => array(
                'label' => '',
                'disable_inarray_validator' => true,
                'empty_option' => 'Selecione uma opção',
                'value_options' => array(
                    'S' => 'Sim',
                    'N' => 'Não'
                ),
            ),
            'attributes' => array(
                'value'            => 'N',
                'class'            => 'form-control select',
                'data-live-search' => "true"
            )
        ));


        $this->add(array(
            'type' => 'Select',
            'name' => 'repetir_quando',
            'options' => array(
                'label' => '',
                'disable_inarray_validator' => true,
                'empty_option' => 'Selecione uma opção',
                'value_options' => array(
                    'DIA' => 'Dias',
                    'SEM' => 'Semanas',
                    'MES' => 'Meses',
                    'ANO' => 'Anos',
                ),
            ),
            'attributes' => array(
                'value'            => 'MEM',
                'class'            => 'form-control select',
                'data-live-search' => "true"
            )
        ));


        $this->add(array(
            'name' => 'repetir_ocorrencia',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'            => 'form-control',
                'placeholder'      => 'Quantidade de parcelas',
            ),
        ));

        $this->add(array(
            'name' => 'anexo',
            'type' => 'File',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'           => 'form-control',
                'placeholder'     => '',
            ),
        ));


        $this->add(array(
            'name' => 'obs',
            'type' => 'Textarea',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'           => 'form-control',
                'rows'            => '5',
                'placeholder'     => 'Escreva alguma coisa sobre a despesa.',
                'data-calendario' => 'data-br1',
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
