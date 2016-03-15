<?php

/*
  Criado     : 20/11/2015
  Modificado : 20/11/2015
  Autor      :
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel por todas as validações e regras de negócio
 */


namespace Despesa\Model\Filtro;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class DespesaCategoriaFiltro implements InputFilterAwareInterface
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


            /* @campo       id
             * @filters     Int
             */
            $inputFilter->add(array(
                'name' => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));


            /* @campo       nome
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             * @validators  Validador\TesteUnicidade
             */
            $inputFilter->add(array(
                'name' => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
//                    array(
//                        'name' => 'NotEmpty',
//                        'break_chain_on_failure' => true,
//                        'options' => array(
//                            'messages' => array(
//                                'isEmpty' => 'Campo obrigatório.'
//                            ),
//                        ),
//                    ),
//                    array(
//                        'name' => 'StringLength',
//                        'break_chain_on_failure' => true,
//                        'options' => array(
//                            'encoding' => 'UTF-8',
//                            'min' => 3,
//                            'max' => 40,
//                            'messages' => array(
//                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
//                                'stringLengthTooLong'  => 'O valor digitado deve possuir no maximo %max% caractere'
//                            )
//                        ),
//                    ),
//                    array(
//                        'name' => 'Validador\TesteUnicidade',
//                        'break_chain_on_failure' => true,
//                        'options' => array(
//                            'adapter'     => $this->dbAdapter,
//                            'tabela'      => 'despesas_categorias',
//                            'campo'       => 'nome',
//                            'camposExtra' => array('fk_cliente'),
//                            'negar'       => array('id'),
//                        ),
//                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}