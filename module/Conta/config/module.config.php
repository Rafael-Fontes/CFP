<?php

namespace Conta;

return array(
    'router' => array(
        'routes' => array(
            'tipo' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/tipo',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Conta\Controller',
                        'controller'    => 'Tipo',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'paginator' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[page/:page]',
                            'constraints' => array(                                
                                'page'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'page' => 1,
                            ),
                        ),
                    ),
                ),
            ),            
          
            'conta' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/conta',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Conta\Controller',
                        'controller'    => 'Conta',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'paginator' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[page/:page]',
                            'constraints' => array(                                
                                'page'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'page' => 1,
                            ),
                        ),
                    ),
                ),
            ),
            
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Conta\Controller\Tipo'  => Controller\TipoController::class,
            'Conta\Controller\Conta' => Controller\ContaController::class,
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../../Cliente/view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../../Cliente/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../Cliente/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    
    'navigation' => array(
        'default' => array(
            'Conta' => array(
                'label' => 'Conta',
                'route' => 'conta',
                'pages' => array(
                    array(
                        'label'  => 'Lista',
                        'route'  => 'conta',
                        'action' => 'index',
                    ),
                    array(
                        'label'  => 'Editar conta',
                        'route'  => 'conta/default',
                        'action' => 'editar'
                    ),
                    array(
                        'label'  => 'Nova conta',
                        'route'  => 'conta/default',
                        'action' => 'salvar'
                    ),
                    array(
                        'label'  => 'Deletar conta',
                        'route'  => 'conta/default',
                        'action' => 'deletar'
                    ),
                ),
            ),
        )
    ),
);
