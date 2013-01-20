<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:tipo]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
              
              /*
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                   
                    'action' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '[:action][/:msg_id]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'index'
                            )
                        )
                    ),
                    

                    
                ),
                
                */
            ),
            
			
            
            'home-message' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/[:tipo][/:ref][/:cod_msg]',
                    'constraints' => array(
                        'tipo'=> '[a-z]+',
                        'cod_msg'=> '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'faleconosco' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/faleconosco',
                    'defaults' => array(
                      'controller' => 'Application\Controller\FaleConosco',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'afiliados' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/afiliados',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Afiliados',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'quem-somos' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/quem-somos',
                    'defaults' => array(
                      'controller' => 'Application\Controller\QuemSomos',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'faq' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/faq',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Faq',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'login-iframe' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login-iframe',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Index',
                        'action'  => 'loginIframe'
                        
                    )
                )
            ),
            'login' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                      'controller' => 'Login\Controller\Login',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'reminder' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/reminder',
                    'defaults' => array(
                      'controller' => 'Login\Controller\Reminder',
                      'action'  => 'index'
                        
                    )
                )
            ),
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
            'changepassword' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/changepassword[/:email][/:token]',
                    'defaults' => array(
                        'controller' => 'Login\Controller\ChangePassword',
                        'action'     => 'index',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'action' => 'logout',
                        'controller'=>'Login\Controller\Login'
                    ),
                ),
            ),
            'area-edit' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/edit[/:area][/:action][/:area-sel]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Area',
                        'action'     => 'index',
                    ),
                ),
            ),
            'area-edit-myimg' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/edit/myimg[/:img-sel]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Area',
                        'action'     => 'myimg',
                    ),
                ),
            ),
            'area-add' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/add[/:area]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'add',
                    ),
                ),
            ),
            'area-remove' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/remove[/:area]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'remove',
                    ),
                ),
            ),
            
			'carrinho' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/carrinho[/:area]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrinho',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'finish-carrinho' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/carrinho/finish',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrinho',
                        'action'     => 'finish',
                    ),
                ),
            ),
            
			'minha-conta' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/minhaconta',
                    'defaults' => array(
                        'controller' => 'Application\Controller\MinhaConta',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'change-my-data' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/changemydata',
                    'defaults' => array(
                        'controller' => 'Application\Controller\ChangeMyData',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'list-areas' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/listareas',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'list-areas',
                    ),
                ),
            ),
            
			'credito-post' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/creditopost',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Credito',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            
        ),    
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'       => 'Application\Controller\IndexController',
            'Application\Controller\Carrinho'    => 'Application\Controller\CarrinhoController',
            'Application\Controller\FaleConosco' => 'Application\Controller\FaleConoscoController',
            'Application\Controller\Afiliados' 	 => 'Application\Controller\AfiliadosController',
            'Application\Controller\QuemSomos' 	 => 'Application\Controller\QuemSomosController',
            'Application\Controller\Faq' 	 	 => 'Application\Controller\FaqController',
            'Application\Controller\Area'        => 'Application\Controller\AreaController',
            'Application\Controller\MinhaConta'  => 'Application\Controller\MinhaContaController',
            'Application\Controller\ChangeMyData'=> 'Application\Controller\ChangeMyDataController',
            'Application\Controller\Credito'	 => 'Application\Controller\CreditoController',
            'Login\Controller\Login' 			 => 'Login\Controller\LoginController',
            'Login\Controller\Reminder' 		 => 'Login\Controller\ReminderController',
            'Login\Controller\Register' 		 => 'Login\Controller\RegisterController',
            'Login\Controller\ChangePassword' 	 => 'Login\Controller\ChangePasswordController',
            
        ),
    ),
    
    'module_layouts' => array(
      'Application' => 'layout/layout',
      //'Login'       => 'layout/layout'
      //'Login'       => 'layout/layout-login'
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
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
                ),
            ),
        ),
    ),
);