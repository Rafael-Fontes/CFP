<?php

namespace Conta;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Conta\Model\BdTabela\TipoTabela;
use Conta\Form\TipoForm;
use Conta\Model\Filtro\TipoFiltro;

use Conta\Model\BdTabela\ContaTabela;
use Conta\Form\ContaForm;
use Conta\Model\Filtro\ContaFiltro;




class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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




    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Conta\Model\BdTabela\ContaTabela' => function($sm){
                    $conta = new ContaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $conta;
                },
                'Conta\Form\ContaForm' => function($sm){
                  $contaForm   = new ContaForm();
                  $contaFiltro = new ContaFiltro($sm->get('Zend\Db\Adapter\Adapter'));

                  $contaForm->setInputFilter($contaFiltro->getInputFilter());
                  return $contaForm;
                },

                'Conta\Model\TipoTabela' =>  function($sm) {
                    $tipo = new TipoTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $tipo;
                },
                'Conta\Form\TipoForm' => function($sm){
                    $tipoForm   = new TipoForm();
                    $tipoFiltro = new TipoFiltro($sm->get('Zend\Db\Adapter\Adapter'));

                    $tipoForm->setInputFilter($tipoFiltro->getInputFilter());
                    return $tipoForm;
                }
            ),
        );
    }
}
