<?php

namespace Receita;


use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Receita\Model\BdTabela\ReceitaCategorioriaTabela;
use Receita\Model\Filtro\ReceitaCategoriaFiltro;
use Receita\Form\ReceitaCategoriaForm;

use Receita\Model\BdTabela\ReceitaSubcategoriaTabela;
use Receita\Model\Filtro\ReceitaSubcategoriaFiltro;
use Receita\Form\ReceitaSubcategoriaForm;

use Receita\Model\BdTabela\ReceitaTabela;
use Receita\Model\Filtro\ReceitaFiltro;
use Receita\Form\ReceitaForm;



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
                'Receita\Model\BdTabela\ReceitaCategorioriaTabela' => function($sm){
                    $receitaCat = new ReceitaCategorioriaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $receitaCat;
                },
                'Receita\Form\ReceitaCategoriaForm' => function($sm){
                  $receitaCatForm   = new ReceitaCategoriaForm();  
                  $receitaCatFiltro = new ReceitaCategoriaFiltro($sm->get('Zend\Db\Adapter\Adapter'));
                  
                  $receitaCatForm->setInputFilter($receitaCatFiltro->getInputFilter());                  
                  return $receitaCatForm;
                },
                        
                'Receita\Model\BdTabela\ReceitaSubcategoriaTabela' => function($sm){
                    $receitaSub = new ReceitaSubcategoriaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $receitaSub;
                },
                'Receita\Form\ReceitaSubcategoriaForm' => function($sm){
                  $receitaSubForm   = new ReceitaSubcategoriaForm();  
                  $receitaSubFiltro = new ReceitaSubcategoriaFiltro($sm->get('Zend\Db\Adapter\Adapter'));
                  
                  $receitaSubForm->setInputFilter($receitaSubFiltro->getInputFilter());                  
                  return $receitaSubForm;
                },
                        
                'Receita\Model\BdTabela\ReceitaTabela' => function($sm){
                    $receitaSub = new ReceitaTabela($sm->get('Zend\Db\Adapter\Adapter'));
                    return $receitaSub;
                },
                'Receita\Form\ReceitaForm' => function($sm){
                  $receitaForm   = new ReceitaForm();  
                  $receitaFiltro = new ReceitaFiltro($sm->get('Zend\Db\Adapter\Adapter'));
                  
                  $receitaForm->setInputFilter($receitaFiltro->getInputFilter());                  
                  return $receitaForm;
                },
                
            ),
        );
    }
}
