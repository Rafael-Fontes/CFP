<?php

namespace Cliente\Model\Filtro;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class ClienteFiltro implements InputFilterAwareInterface
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
                'filters' => array(
                    array('name' => 'Int'),
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
                                'isEmpty' => 'Campo obrigatório.'
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


            /* @campo       email
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             * @validators  EmailAddress
             * @validators  TesteUnicidade
             */
            $inputFilter->add(array(
                'name' => 'email',
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
                                'isEmpty' => 'Campo obrigatório.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 100,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no maximo %max% caractere'
                            )
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
                    array(
                        'name' => 'Utilitario\Validacao\TesteUnicidade',
                        'options' => array(
                            'adapter' => $this->dbAdapter,
                            'tabela'  => 'clientes',
                            'campo'   => 'email',
                            'negar'   => array('id'),
                        ),
                    ),
                ),
            ));


            /* @campo       senha
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             */
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
                                'isEmpty' => 'Campo obrigatório.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 20,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no maximo %max% caractere'
                            )
                        ),
                    ),
                ),
            ));


            /* @campo       senha_confirma
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  identical
             */
            $inputFilter->add(array(
                'name' => 'senha_confirma',
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
                                'isEmpty' => 'Campo obrigatório.'
                            ),
                        ),
                    ),
                    array(
                        'name'    => 'identical',
                        'options' => array(
                            'token' => 'senha',
                            'messages' => array(
                                'notSame'      => 'Senhas não são iguais.',
                                'missingToken' => 'Digite essa mesma senha no campo senha'
                            )
                        )
                    ),
                ),
            ));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}