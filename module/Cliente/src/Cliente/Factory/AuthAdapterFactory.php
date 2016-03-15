<?php

/*
  Criado     : 26/12/2015
  Modificado : 26/12/2015
  Autor      : Raphaell
  Contato    :
  Descrição  :

        Usado no module.confing.php para gerar um serviço AuthenticationService
 */


namespace Cliente\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Cliente\Model\BdTabela\AuthTabela;
use Cliente\Adapter\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
    /*
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Cliente\Adapter\AuthAdapter
    */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $authTabela = new AuthTabela($dbAdapter);
        $bdTabela   = new AuthAdapter($authTabela);
        return $bdTabela;
    }
}
