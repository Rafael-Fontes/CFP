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

use Cliente\Model\Filtro\AuthFiltro;

class AuthFormFactory implements FactoryInterface
{
    /*
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Cliente\Form\AuthForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authForm = $serviceLocator->get('Cliente\Form\AuthFormB');
        $authFiltro = new AuthFiltro();

        $authForm->setInputFilter($authFiltro->getInputFilter());
        return $authForm;
    }
}
