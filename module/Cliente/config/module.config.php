<?php

namespace Cliente;

return array(
    'router' => array(
        'routes' => array(
            'cliente' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/cliente',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cliente\Controller',
                        'controller'    => 'Cliente',
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

            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cliente\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'login',
                    ),
                ),
            ),

            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cliente\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'logout',
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
            'navigation'                                => 'Zend\Navigation\Service\DefaultNavigationFactory',            
            'Zend\Authentication\AuthenticationService' => 'Cliente\Factory\AuthFactory',
            'Cliente\Model\BdTabela\ClienteTabela'      => 'Cliente\Factory\ClienteTabelaFactory',
            'Cliente\Form\ClienteForm'                  => 'Cliente\Factory\ClienteFormFactory',
            'Cliente\Adapter\AuthAdapter'               => 'Cliente\Factory\AuthAdapterFactory',
            'Cliente\Form\AuthForm'                     => 'Cliente\Factory\AuthFormFactory',
        ),
        'invokables' => array(
            'Cliente\Form\ClienteFormB' => 'Cliente\Form\ClienteForm',
            'Cliente\Form\AuthFormB'    => 'Cliente\Form\AuthForm',
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Cliente\Controller\Cliente'  => Controller\ClienteController::class,
            'Cliente\Controller\Auth'     => Controller\AuthController::class,
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
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
            array(
                'label' => 'Cliente',
                'route' => 'cliente',
                'pages' => array(
                    array(
                        'label' => 'Lista',
                        'route' => 'cliente',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Informações',
                        'route' => 'cliente/default',
                        'action' => 'vizualizar',
                    ),
                    array(
                        'label' => 'Editar registro',
                        'route' => 'cliente/default',
                        'action' => 'editar',
                    ),
                    array(
                        'label' => 'Novo registro',
                        'route' => 'cliente/default',
                        'action' => 'salvar',
                    ),
                    array(
                        'label' => 'Deletar registro',
                        'route' => 'cliente/default',
                        'action' => 'deletar',
                    ),
                ),
            ),
        ),
    ),
);
