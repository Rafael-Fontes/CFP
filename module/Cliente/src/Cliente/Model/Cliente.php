<?php

/*
  Criado     : 23/09/2015
  Modificado : 13/12/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
               Classe de entidade
 */


namespace Cliente\Model;

use Zend\Stdlib\Hydrator\ClassMethods;


class Cliente
{

    private $id;
    private $nome;
    private $email;
    private $salt;
    private $senha;
    private $criado;
    private $modificado;
    private $situacao;



    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = (empty($salt)) ? $this->gerarSalt() : $salt;
        return $this;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        /*
         * A senha deve conter no minimo 6 caracteres e no máximo 20.
         * Se o numero de caracteres da $senha for menor ou igual a 20, a mesma deve ser
         * criptografada.
         */
        $this->senha = ($senha && strlen($senha) <= 20) ? $this->hasShenha($senha) : $senha;
        return $this->senha;
    }

    public function getCriado()
    {
        return $this->criado;
    }

    public function setCriado($criado)
    {
        $this->criado = $criado;
        return $this;
    }

    public function getModificado()
    {
        return $this->modificado;
    }

    public function setModificado($modificado)
    {
        $this->modificado = $modificado;
        return $this;
    }

    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }





    /*
     * @param Array $dados
     * @return Obj
     */
    public function exchangeArray(Array $dados = array())
    {
        if(array_key_exists('senha', $dados) && !empty($dados['senha'])){
            $this->setSalt(null);
        }

        (new ClassMethods())->hydrate($dados, $this);
    }





    /*
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
        //return (new ClassMethods)->extract($this);
    }





    /*
     * Função deve ser usada para criptografar a senha do cliente
     *
     * @param  String $senha
     * @return String
     */
    public function hasShenha($senha)
    {
        if(empty($this->getSalt())){
            throw new \Exception("Erro ao gerar o hash.");
        }

        $hashSenha = hash('sha512', $senha . $this->getSalt());

        for($i = 0; $i < 64000; $i++) {
            $hashSenha = hash ('sha512', $hashSenha);
        }

        return $hashSenha;
    }





    /*
     * Função deve ser utilizada para gerar o salt do cliente
     * @return String
     */
    private function gerarSalt()
    {
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        return $salt;
    }
}