<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\ReminderForm;

class ReminderController extends AbstractActionController {
    
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
        $form = $this->getServiceLocator()->get("service_register_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $obj_post = $request->getPost();
            
            $repository = $this->getEm()->getRepository("Login\Entity\Users");
            $obj_records_users = $repository->findByEmail($obj_post->email);
            
            $records = $obj_records_users->getArrayCopy();
            
            $form->setData($obj_post);
            if ($form->isValid() && $records['email'] == $obj_post->email) {
                echo "é valido";
                /*
                $service = $this->getServiceLocator()->get("service_register");
                $records = $request->getPost()->toArray();
                $records['token'] = md5(uniqid(time()));
                //var_dump($records);
                $service->insert($records);
                $service->SendEmail($records);    
                return $this->redirect()->toRoute('home');*/
            }
        }

        return new ViewModel(array('form' => $form));    

    }
    


}
