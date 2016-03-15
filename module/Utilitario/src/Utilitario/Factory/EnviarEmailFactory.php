<?php

/*
  Criado     : 27/12/2015
  Modificado : 27/12/2015
  Autor      : Raphaell
  Contato    :
  Descrição  :

        Usado no module.confing.php para gerar um serviço
 */


namespace Utilitario\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Utilitario\Servico\EnviarEmail;

class EnviarEmailFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $confingMail = $serviceLocator->get('Config');
        return new EnviarEmail($confingMail['email']);
    }

}
