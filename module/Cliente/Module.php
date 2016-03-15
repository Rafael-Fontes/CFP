<?php

namespace Cliente;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;




class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

//        $sharedEvents = $eventManager->getSharedManager();
//        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController',
//                MvcEvent::EVENT_DISPATCH, array($this, 'validacaoAuth'), 99);
    }




    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }




    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }





    /*
     * Verifica se o usuário esta logado.
     */
    public function validacaoAuth($e)
    {
        $auth       = $e->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
        $controller = $e->getTarget();
        $rota       = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

        if ($auth->hasIdentity() && $rota == 'login') {
            return $controller->redirect()->toRoute('cliente');
        }

        if (!$auth->hasIdentity() && $rota !== 'login') {
            return $controller->redirect()->toRoute('login');
        }
    }
}
