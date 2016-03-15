<?php

namespace Despesa;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Despesa\Model\BdTabela\DespesaTabela;
use Despesa\Form\DespesaForm;
use Despesa\Model\Filtro\DespesaFiltro;

use Despesa\Model\BdTabela\DespesaCategoriaTabela;
use Despesa\Form\DespesaCategoriaForm;
use Despesa\Model\Filtro\DespesaCategoriaFiltro;

use Despesa\Model\BdTabela\DespesaSubcategoriaTabela;
use Despesa\Form\DespesaSubcategoriaForm;
use Despesa\Model\Filtro\DespesaSubcategoriaFiltro;


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
                'Despesa\Model\BdTabela\DespesaCategoriaTabela' => function($sm){
                    $despesaCat = new DespesaCategoriaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $despesaCat;
                },
                'Despesa\Form\DespesaCategoriaForm' => function($sm){
                  $despesaCatForm   = new DespesaCategoriaForm();  
                  $despesaCatFiltro = new DespesaCategoriaFiltro($sm->get('Zend\Db\Adapter\Adapter'));
                  
                  $despesaCatForm->setInputFilter($despesaCatFiltro->getInputFilter());                  
                  return $despesaCatForm;
                },
                        
                'Despesa\Model\BdTabela\DespesaSubcategoriaTabela' => function($sm){
                    $despesaSub = new DespesaSubcategoriaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $despesaSub;
                },
                'Despesa\Form\DespesaSubcategoriaForm' => function($sm){
                  $despesaSubForm   = new DespesaSubcategoriaForm();  
                  $despesaSubFiltro = new DespesaSubcategoriaFiltro($sm->get('Zend\Db\Adapter\Adapter'));
                  
                  $despesaSubForm->setInputFilter($despesaSubFiltro->getInputFilter());                  
                  return $despesaSubForm;
                },        
                        
                'Despesa\Model\BdTabela\DespesaTabela' => function($sm){
                    $despesaSub = new DespesaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $despesaSub;
                },
                'Despesa\Form\DespesaForm' => function($sm){
                  $despesaForm   = new DespesaForm();  
                  $despesaFiltro = new DespesaFiltro();
                  
                  $despesaForm->setInputFilter($despesaFiltro->getInputFilter());                  
                  return $despesaForm;
                },        
            ),
        );
    }
}
