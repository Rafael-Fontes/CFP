<?php

namespace Cliente\Form;

use Zend\Form\Form;


class ClienteForm extends Form{


    public function __construct($name = null) {

        parent::__construct('cliente');


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
                'placeholder'   => 'Digite seu nome',
            ),
        ));



        $this->add(array(
            'name'    => 'email',
            'type'    => 'Text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
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
                'id'            => '',
                'placeholder'   => 'Digite sua senha',
            ),
        ));



        $this->add(array(
            'name' => 'senha_confirma',
            'type' => 'Password',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'placeholder'   => 'Digite sua senha novamente',
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
