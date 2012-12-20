<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\ReminderForm;

class ChangePasswordController extends AbstractActionController {
    
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
        $token = $this->params('token', false);
        $email = $this->params('email', false);
        $error = false;
        
        if($token && $email){
            
            $repository = $this->getEm()->getRepository("Login\Entity\Users");
            $obj_records_users = $repository->findByTokenAndEmail($token,$email);
            //var_dump($repository,$obj_records_users);
            $form = $this->getServiceLocator()->get("service_changepassword_form");
            if(!empty($obj_records_users)){
               $records = $obj_records_users->getArrayCopy();
               
               
               
               $request = $this->getRequest();
               $recordBase['id'] = $records['id']; 
               $form->setData($recordBase);
               
               if($request->isPost()){
                  $records_post = $request->getPost()->toArray();
                  $form->setData($records_post);
                 
                  if ($form->isValid()) {
                    $service = $this->getServiceLocator()->get("service_changepassword");
                    $data = new \DateTime("now America/Sao_Paulo");
                    $records_post['data_alteracao']  = $data;
                    $records_post['senha'] = $service->encryptPassword($records_post['senha']);
                    $service->update($records_post);
                    $error = 2;
                    //return $this->redirect()->toRoute('home');
                  }
               }
               
                
            }else{
                $error = 1; //usuario nao encontrado
            }
            
        }else{
            return $this->redirect()->toRoute('home');
        }
        
        return new ViewModel(array('form' => $form,'msg'=>$error));    

    }
    


}
