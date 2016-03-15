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

use Cliente\Model\BdTabela\ClienteTabela;

class ClienteTabelaFactory implements FactoryInterface
{
    
     /*
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Cliente\Model\BdTabela\ClienteTabela
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        return new ClienteTabela($dbAdapter);
    }
}
