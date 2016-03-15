<?php

/*
  Criado     : 23/11/2015
  Modificado : 23/11/2015
  Autor      :
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel por todas as validações e regras de negócio
 */

namespace Despesa\Model\Filtro;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class DespesaFiltro implements InputFilterAwareInterface
{

    protected $inputFilter;



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


            /* @campo       fk_categoria
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  Digits
             */
            $inputFilter->add(array(
                'name'        => 'fk_categoria',
                'required'    => true,
                'allow_empty' => false,
                'filters'     => array(
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


            /* @campo       fk_subcategoria
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  Digits
             */
            $inputFilter->add(array(
                'name'        => 'fk_subcategoria',
                'required'    => false,
                'allow_empty' => true,
                'filters'     => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
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



            /* @campo       fk_conta
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  Digits
             * @validators  NotEmpty
             */
            $inputFilter->add(array(
                'name'        => 'fk_conta',
                'required'    => true,
                'allow_empty' => false,
                'filters'     => array(
                    array('name' => 'htmlentities'),
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



            /* @campo       fk_cartao
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  Digits
             * @validators  NotEmpty
             */
//            $inputFilter->add(array(
//                'name'        => 'fk_cartao',
//                'required'    => true,
//                'allow_empty' => false,
//                'filters'     => array(
//                    array('name' => 'StripTags'),
//                    array('name' => 'StringTrim'),
//                ),
//                'validators' => array(
//                    array(
//                        'name' => 'NotEmpty',
//                        'break_chain_on_failure' => true,
//                        'options' => array(
//                            'messages' => array(
//                                'isEmpty' => 'Campo obrigatório!'
//                            ),
//                        ),
//                    ),
//                    array(
//                        'name' => 'Digits',
//                        'break_chain_on_failure' => true,
//                        'options' => array(
//                            'messages' => array(
//                                'notDigits' => 'Campo só aceita numeros'
//                            )
//                        )
//                    ),
//                ),
//            ));



            /* @campo       descricao
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             */
            $inputFilter->add(array(
                'name'        => 'descricao',
                'required'    => true,
                'allow_empty' => false,
                'filters'     => array(
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
                            'max' => 50,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no máximo %max% caractere'
                            )
                        ),
                    ),
                ),
            ));



            /* @campo       data_vencimento
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  Date
             */
            $inputFilter->add(array(
                'name'     => 'data_vencimento',
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
                        'name' => 'Date',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'format' => 'd/m/Y',
                            'locale' => 'de_AT',
                            'messages' => array(
                                'dateInvalidDate' => 'Data invalida!'
                            )
                        ),
                    ),
                ),
            ));



            /* @campo       valor
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  IsFloat
             */
            $inputFilter->add(array(
                'name'        => 'valor',
                'required'    => true,
                'allow_empty' => false,
                'filters'     => array(
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
                            'min' => 4,
                            'max' => 13,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no máximo %max% caractere'
                            )
                        ),
                    ),
                    array(
                        'name' => 'IsFloat',
                        'options' => array(
                            'locale' => 'de_AT',
                            'messages' => array(
                                'notFloat' => 'Valor diferente do esperado!'
                            )
                        )
                    ),
                ),
            ));


            /* @campo       pagamento
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             */
            $inputFilter->add(array(
                'name'        => 'pagamento',
                'required'    => true,
                'allow_empty' => false,
                'filters'     => array(
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
                            'min' => 1,
                            'max' => 1,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no máximo %max% caractere'
                            )
                        ),
                    ),
                ),
            ));



            /* @campo       pagamento_data
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  ReceitaDataPagamento
             */
            $inputFilter->add(array(
                'name'        => 'pagamento_data',
                'required'    => false,
                'allow_empty' => true,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Despesa\Model\Filtro\Validacao\DespesaDataPagamento'
                    ),
                ),
            ));



            /* @campo       repetir
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  NotEmpty
             * @validators  StringLength
             */
            $inputFilter->add(array(
                'name'        => 'repetir',
                'required'    => true,
                'allow_empty' => false,
                'filters'     => array(
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
                            'min' => 1,
                            'max' => 1,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no máximo %max% caractere'
                            )
                        ),
                    ),
                ),
            ));



            /* @campo       repetir_quando
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  DespesaRepetirQuando
             */
            $inputFilter->add(array(
                'name'        => 'repetir_quando',
                'required'    => false,
                'allow_empty' => false,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Despesa\Model\Filtro\Validacao\DespesaRepetirQuando',
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));



            /* @campo       repetir_ocorrencia
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  DespesaRepetirOcorrencia
             */
            $inputFilter->add(array(
                'name'        => 'repetir_ocorrencia',
                'required'    => false,
                'allow_empty' => false,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Despesa\Model\Filtro\Validacao\DespesaRepetirOcorrencia',
                        'break_chain_on_failure' => true,
                    )              
                ),
            ));



            /* @campo       anexo
             * @filters     StripTags
             * @filters     StringTrim
             * @filters     Zend\Filter\File\RenameUpload
             * @validators  Zend\Validator\File\Size
             * @validators  Zend\Validator\File\MimeType
             * @validators  Zend\Validator\File\Extension
             */
            $inputFilter->add(array(
                'name'        => 'anexo',
                'required'    => false,
                'allow_empty' => false,
                'filters'     => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array(
                        'name'    => 'Zend\Filter\File\RenameUpload',
                        'options' => array(
                            'target'               => 'data/upload/despesa',
                            'use_upload_extension' => true,
                            'randomize'            => true
                        ),                        
                    ),
                ),
                'validators' => array(  
                    array(
                        'name' => 'Zend\Validator\File\Size',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'max' => '1MB',
                            'messages'  => array(
                                'fileSizeTooBig'   => "Tamanho máximo permitido é '%max%', tamanho detectado '%size%' .",
                                'fileSizeNotFound' => "Arquivo não pode ser lido ou não existe. ",
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\File\MimeType',
                        'break_chain_on_failure' => true,
                        'options' => array(                            
                            'mimeType' => array(
                                'image/jpeg',
                                'image/png',
                            ),
                            'messages' => array(
                                'fileMimeTypeFalse'       => 'Tipo do arquivo não permitido.',
                                'fileMimeTypeNotDetected' => 'Tipo do arquivo não pode ser detectado.',
                                'fileMimeTypeNotReadable' => 'Arquivo não pode ser lido ou não existe.',
                            ),
                        )
                    ),
                     array(
                        'name' => 'Zend\Validator\File\Extension',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'extension' => array('jpg','jpeg', 'png'),
                            'messages'  => array(
                                'fileExtensionFalse'    => 'Arquivo apresenta uma extenção não permitida',
                                'fileExtensionNotFound' => 'Arquivo não pode ser lido ou não existe'
                            ),
                        ),
                    ),
                ),
            ));


            /* @campo       repetir_quando
             * @filters     StripTags
             * @filters     StringTrim
             * @validators  StringLength
             */
            $inputFilter->add(array(
                'name'        => 'obs',
                'required'    => false,
                'allow_empty' => true,
                'filters'     => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 255,
                            'messages' => array(
                                'stringLengthTooShort' => 'O valor digitado deve possuir no minimo %min% caractere',
                                'stringLengthTooLong'  => 'O valor digitado deve possuir no máximo %max% caractere'
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