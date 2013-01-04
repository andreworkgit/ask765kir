<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\ModuleManager\ModuleManager;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;
	
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;
   
//use Login\Form\ReminderForm as ReminderForm;    

class Module {

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Login' 	  => __DIR__ . '/src/' . "Login",
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap($e) {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
                    $controller = $e->getTarget();
                    $controllerClass = get_class($controller);
                    $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
                    $config = $e->getApplication()->getServiceManager()->get('config');
                    if (isset($config['module_layouts'][$moduleNamespace])) {
                        $controller->layout($config['module_layouts'][$moduleNamespace]);
                    }
                }, 98);
    }
/*
    public function init(ModuleManager $moduleManager) {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach("LivrariaAdmin", 'dispatch', function($e) {
                    $auth = new AuthenticationService;
                    $auth->setStorage(new SessionStorage("LivrariaAdmin"));

                    $controller = $e->getTarget();
                    $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

                    if (!$auth->hasIdentity() and ($matchedRoute == "livraria-admin" or $matchedRoute == "livraria-admin-interna")) {
                        return $controller->redirect()->toRoute('livraria-admin-auth');
                    }
                }, 99);
    }*/

    public function getServiceConfig() {

        return array(
            'factories' => array(
                
				'service_helper_session_login' => function($service){
					$helper = $service->get('viewhelpermanager')->get('UserIdentity');
					return $helper('Login');
				},
				
                'service_faleconosco_form' => function ($service) {
                     $form = new \Application\Form\FaleConoscoForm();
                     return $form;
                },
                
                'service_faleconosco' => function($service) {
                    $obj = new \Application\Service\FaleConosco($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
				
				'service_register' => function($service) {
                    $obj = new \Login\Service\Register($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'service_reminder' => function($service) {
                    $obj = new \Login\Service\Reminder($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'service_changepassword' => function($service) {
                    $obj = new \Login\Service\ChangePassword($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'Login\Auth\Adapter' => function($service) {
                    return new \Login\Auth\Adapter($service->get('Doctrine\ORM\EntityManager'));
                },
                
                'service_register_form' => function ($service) {
                    $baseUrl = $service->get('request')->getbaseUrl();
                    $form = new \Login\Form\RegisterForm($baseUrl);
                    $emailInput = $form->getInputFilter()->get('email');
                    
                    $NoObjectExistsValidator = new \DoctrineModule\Validator\NoObjectExists(array(
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Users'),
                        'fields'            => 'email',
                        'messages' =>
                            array(
                                'objectFound' => 'Sorry guy, a user with this email %value% already exists !'
                            )
                    ));
                     //var_dump($ObjectExistsValidator->isValid('tess3@gmail.com'));
                    $emailInput->getValidatorChain()->addValidator($NoObjectExistsValidator);
                    return $form;
                    
                },
                'service_reminder_form' => function ($service) {
                    $baseUrl = $service->get('request')->getbaseUrl();
                    
                    $form = new \Login\Form\ReminderForm($baseUrl);
                    
                    $emailInput = $form->getInputFilter()->get('email');
                    $ObjectExistsValidator = new ObjectExistsValidator(array(
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Users'),
                        'fields'            => 'email',
                        'messages' =>
                            array(
                                'noObjectFound' => 'Sorry, this email %value% not exists !'
                            )
                    ));
					
                     //var_dump($ObjectExistsValidator->isValid('tess3@gmail.com'));
                    $emailInput->getValidatorChain()->addValidator($ObjectExistsValidator);
                    
                    return $form;
                    
                },
                'service_register_step2_form' => function ($service) {
                     $form = new \Login\Form\RegisterStep2Form();
                     return $form;
                },
                'service_changepassword_form' => function ($service) {
                     $form = new \Login\Form\ChangePasswordForm();
                     return $form;
                },
                'resolver_files' => function($sm) {
                  
                  $map = new \Zend\View\Resolver\TemplateMapResolver(array(
                            'login/register/confirmation-email' => __DIR__ . '/view/login/register/confirmation-email.phtml',
                        ));
                  return new \Zend\View\Resolver\TemplateMapResolver($map);
                }
                
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'UserIdentity' => new View\Helper\UserIdentity()
            )
        );
    }


}
