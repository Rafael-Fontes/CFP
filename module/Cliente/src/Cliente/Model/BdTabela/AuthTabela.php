<?php

namespace Cliente\Model\BdTabela;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Cliente\Model\Cliente;


class AuthTabela extends AbstractTableGateway
{

    protected $table = 'clientes';


    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Cliente());
        $this->initialize();
    }





    /*
     * Verifica se o usuário informado existe.
     *
     * @param  String $login
     * @param  String $senha
     * @return Obj|Boolean
     */
    public function vefiricarLoginSenha($login, $senha)
    {
        $login = filter_var($login, FILTER_SANITIZE_STRING);
        $senha = filter_var($senha, FILTER_SANITIZE_STRING);

        $select = new Select();
        $select->from('clientes')
                ->where(array('email' => $login));

        $objCliente = $this->selectWith($select)
                           ->current();

        if($objCliente)
        {
            $clienteSenha = $objCliente->hasShenha($senha);
            if($objCliente->getSenha() == $clienteSenha)
            {
                $objCliente->setSenha(null);
                $objCliente->setSalt(1);

                return $objCliente;
            }

            return false;
        }

        return false;
    }

}