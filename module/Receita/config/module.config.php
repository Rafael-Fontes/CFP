<?php

namespace Receita;

return array(
    'router' => array(
        'routes' => array(
            'receita' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/receita',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Receita\Controller',
                        'controller'    => 'Receita',
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
          
            'receita-categoria' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/receita-categoria',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Receita\Controller',
                        'controller'    => 'ReceitaCategoria',
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
            
            'receita-subcategoria' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/receita-subcategoria',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Receita\Controller',
                        'controller'    => 'ReceitaSubcategoria',
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
    ),
    
    'controllers' => array(
        'invokables' => array(
            'Receita\Controller\ReceitaCategoria'     => Controller\ReceitaCategoriaController::class,
            'Receita\Controller\ReceitaSubcategoria'  => Controller\ReceitaSubcategoriaController::class,
            'Receita\Controller\Receita'              => Controller\ReceitaController::class,
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
    
    //Configuracao do breadcrumb
    'navigation' => array(
        'default' => array(
            'Receita' => array(
                'label' => 'Receitas',
                'route' => 'receita',
                'pages' => array(
                    array(
                        'label' => 'Lista',
                        'route' => 'receita',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Editar receita',
                        'route' => 'receita/default',
                        'action' => 'editar',
                    ),
                    array(
                        'label' => 'Nova receita',
                        'route' => 'receita/default',
                        'action' => 'salvar',
                    ),                    
                    array(
                        'label' => 'Deletar receita',
                        'route' => 'receita/default',
                        'action' => 'deletar',
                    ),                    
                ),
            ),            
            
            'ReceitaCategoria' => array(
                'label' => 'Categoria das receitas',
                'route' => 'receita-categoria',
                'pages' => array(
                    array(
                        'label' => 'Lista',
                        'route' => 'receita-categoria',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Editar categoria',
                        'route' => 'receita-categoria/default',
                        'action' => 'editar',
                    ),
                    array(
                        'label' => 'Nova categoria',
                        'route' => 'receita-categoria/default',
                        'action' => 'salvar',
                    ),                    
                    array(
                        'label' => 'Deletar categoria',
                        'route' => 'receita-categoria/default',
                        'action' => 'deletar',
                    ),                    
                ),
            ),
            
            'ReceitaSubcategoria' => array(
                'label' => 'Subcategoria das receitas',
                'route' => 'receita-subcategoria',
                'pages' => array(
                    array(
                        'label' => 'Lista',
                        'route' => 'receita-subcategoria',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Editar subcategoria',
                        'route' => 'receita-subcategoria/default',
                        'action' => 'editar',
                    ),
                    array(
                        'label' => 'Nova subcategoria',
                        'route' => 'receita-subcategoria/default',
                        'action' => 'salvar',
                    ),                    
                    array(
                        'label' => 'Deletar subcategoria',
                        'route' => 'receita-subcategoria/default',
                        'action' => 'deletar',
                    ),                    
                ),
            ),
            
        ),
    ),
);
