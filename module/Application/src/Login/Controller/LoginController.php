<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

class LoginController extends AbstractActionController {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
        /*
     * @return EntityManager
     */

    protected function getEm() {
        if (null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->em;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $error = false;
        
        $form = new \Login\Form\LoginForm($request->getbaseUrl());
        
        if ($request->isPost()) {
            $obj_post = $request->getPost();
            $form->setData($obj_post);
            if ($form->isValid()) {
              
              //convert to array
              $obj_post_array = $obj_post->toArray();
              
              $auth = new AuthenticationService;

              $sessionStorage = new SessionStorage("Login");
              $auth->setStorage($sessionStorage);
                
              $service = $this->getServiceLocator()->get("service_changepassword");
              $authAdapter = $this->getServiceLocator()->get('Login\Auth\Adapter');
              $authAdapter->setUsername($obj_post_array['email'])
                          ->setPassword($service->encryptPassword($obj_post_array['senha']));
        
              $result = $auth->authenticate($authAdapter);
				
			  if ($result->isValid()) {
                  //var_dump($auth->getIdentity());
                  $getIdentity = $result->getIdentity();
                  $getIdentity['user']->senha = null;
                  
                  //var_dump($getIdentity);exit;
                  //$sessionStorage->write($getIdentity['user'], null);
                  echo "Logado com sucesso";
                  //return $this->redirect()->toRoute("livraria-admin", array('controller' => 'categorias'));
              }else{
              	  $getIdentity = $result->getIdentity();
                  //var_dump($getIdentity);
                  
                  if(!empty($getIdentity)):
                    //echo "No momento seu e-mail não foi confirmado. Clique aqui para confirmar.";
                    $msg['ferror']['ref'] 	  = "login";	
				  	$msg['ferror']['cod_msg'] = "2";
           	   	  else:
                  	$msg['ferror']['ref'] 	  = "login";	
				  	$msg['ferror']['cod_msg'] = "1";
				  endif;
              }
              
            }
        }

        return new ViewModel(array('form' => $form,'dados' => $msg));    

    }
    


}
