<?php

/*
  Criado     : 26/12/2015
  Modificado : 26/12/2015
  Autor      : Raphaell
  Contato    :
  Descrição  :

        Usado no module.confing.php para gerar um serviço
 */


namespace Cliente\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Cliente\Model\Filtro;

class ClienteFormFactory implements FactoryInterface
{
    /*
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Cliente\Form\ClienteForm
    */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapeter    = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $clienteForm   = $serviceLocator->get('Cliente\Form\ClienteFormB');
        $clienteFiltro = new Filtro\ClienteFiltro($dbAdapeter);
        
        return $clienteForm->setInputFilter($clienteFiltro->getInputFilter());
    }
}
