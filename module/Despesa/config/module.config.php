<?php

namespace Despesa;

return array(
    'router' => array(
        'routes' => array(
            
            'despesa' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/despesa',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Despesa\Controller',
                        'controller'    => 'Despesa',
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
            
            'despesa-categoria' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/despesa-categoria',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Despesa\Controller',
                        'controller'    => 'DespesaCategoria',
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

            'despesa-subcategoria' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/despesa-subcategoria',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Despesa\Controller',
                        'controller'    => 'DespesaSubcategoria',
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
        'factories' => array(),
    ),

    'controllers' => array(
        'invokables' => array(
            'Despesa\Controller\DespesaCategoria'    => Controller\DespesaCategoriaController::class,
            'Despesa\Controller\DespesaSubcategoria' => Controller\DespesaSubcategoriaController::class,
            'Despesa\Controller\Despesa'             => Controller\DespesaController::class
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
            'application/index/index' => __DIR__ . '/../../Cliente/view/application/index/index.phtml',
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
    
    
    //Configuracao do breadcrumb
    'navigation' => array(
        'default' => array(
            'Despesa' => array(
                'label' => 'Despesa',
                'route' => 'despesa',
                'pages' => array(
                    array(
                        'label' => 'Lista',
                        'route' => 'despesa/default/[page/:page]',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Editar despesa',
                        'route' => 'despesa/default',
                        'action' => 'editar',
                    ),
                    array(
                        'label' => 'Nova despesa',
                        'route' => 'despesa/default',
                        'action' => 'salvar',
                    ),                    
                    array(
                        'label' => 'Deletar despesa',
                        'route' => 'despesa/default',
                        'action' => 'deletar',
                    ),                    
                ),
            ),
            
//            'Despesa-Categoria' => array(
//                'label' => 'D'
//            ),
        )
    ),
);
