<?php


namespace Cliente\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

use Cliente\Model\BdTabela\AuthTabela;


class AuthAdapter implements AdapterInterface
{
    
    protected $authTabela;
    protected $login;
    protected $senha;
    
    
    
    public function __construct(AuthTabela $authTabela)
    {
        $this->authTabela = $authTabela;
    }
    
    
    
    
    
    public function getLogin()
    {
        return $this->login;
    }

    
    
    
    
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    
    
    
    
    public function getSenha()
    {
        return $this->senha;
    }

    
    
    
    
    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }



    
    
    public function authenticate()
    {
        $cliente = $this->authTabela->vefiricarLoginSenha($this->getLogin(), $this->getSenha());
        if($cliente)
        {
            return new Result(Result::SUCCESS, array('cliente' => $cliente), array('Login efetuado com sucesso.'));
        }
        else
        {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array());
        }
    }

}
