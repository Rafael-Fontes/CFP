<?php

/*
  Criado     : 22/09/2015
  Modificado : 17/11/2015
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


class ContaFiltro implements InputFilterAwareInterface
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



            /* @campo       fk_tipo
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  Digits
             */
            $inputFilter->add(array(
                'name'        => 'fk_tipo',
                'required'    => true,
                'allow_empty' => false,
                'filters'  => array(
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
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                'notDigits' => 'Campo só aceita numeros'
                            )
                        )
                    ),
                ),
            ));


            /* @campo       fk_cliente
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  Digits
             */
            $inputFilter->add(array(
                'name' => 'fk_cliente',
                'required' => true,
                'filters'  => array(
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
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                'notDigits' => 'Campo só aceita numeros'
                            )
                        )
                    ),
                ),
            ));


            /* @campo       saldo
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             */
            $inputFilter->add(array(
                'name' => 'saldo',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'IsFloat',
                        'options' => array(
                            'locale' => 'de_AT',
                            'messages' => array(
                                'notFloat' => 'Valor diferente do esperado!'
                            )
                        ),
                    )
                ),
            ));


            /* @campo       nome
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             */
            $inputFilter->add(array(
                'name' => 'nome',
                'required' => true,
                'filters'  => array(
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


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
