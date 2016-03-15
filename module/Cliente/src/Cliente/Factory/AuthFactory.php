<?php

/*
  Criado     : 17/12/2015
  Modificado : 17/12/2015
  Autor      : Raphaell
  Contato    :
  Descrição  :

        Usado no module.confing.php para gerar um serviço AuthenticationService
 */


namespace Cliente\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;



class AuthFactory implements FactoryInterface
{
    /*
     * @param  ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
    */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Cliente\Adapter\AuthAdapter');        
        return new AuthenticationService(new SessionStorage('Cliente'), $adapter);
    }
}
