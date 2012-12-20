<?php

namespace Login;


return array(
    'controllers' => array(
        'invokables' => array(
            'Login\Controller\Login' => 'Login\Controller\LoginController',
            'Login\Controller\Register' => 'Login\Controller\RegisterController',
            'Login\Controller\Reminder' => 'Login\Controller\ReminderController'
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'register' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/register',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Login\Controller',
                        'controller'    => 'Login\Controller\Register',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // Segment route for viewing one blog post
                    'post' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[:action][/:id]',
                            'defaults' => array(
                                'action' => 'generation'
                            )
                        )
                    ),
                    'step2' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/step2[/:token]',
                            'defaults' => array(
                              'action' => 'step2'
                            )
                        )
                    ),
                )
                
            ),
            'login' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Login\Controller\Login',
                        'action'     => 'index',
                    ),
                ),
            ),
            'reminder' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/reminder',
                    'defaults' => array(
                        'controller' => 'Login\Controller\Reminder',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            /*
            'login' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/login[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Login\Controller\Login',
                        'action'     => 'index',
                    ),
                ),
            ),*/
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'login' => __DIR__ . '/../view',
        ),
    ),
    
    
    // Doctrine config
    'doctrine' => array(
            'driver' => array(
                __NAMESPACE__ . '_driver' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
                ),
                'orm_default' => array(
                    'drivers' => array(
                        __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                    )
                )
            )
    )

    
);