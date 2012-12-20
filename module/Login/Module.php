<?php

namespace Login;

//use Album\Model\AlbumTable;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;


class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
            'invokables' => array(
                //'login_register_form' => 'Login\Form\RegisterForm'
            ),
            'factories' => array(
                /*'Album\Model\AlbumTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AlbumTable($dbAdapter);
                    return $table;
                },*/
                
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
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Login\Entity\Users'),
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
                    
                    $ObjectExistsValidator = new \DoctrineModule\Validator\ObjectExists(array(
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Login\Entity\Users'),
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}