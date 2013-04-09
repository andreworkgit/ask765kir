<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FaleConoscoController extends AbstractActionController {
    
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
    
    public function indexAction() {

        
        $form = $this->getServiceLocator()->get("service_faleconosco_form");
        $request = $this->getRequest();

        //var_dump($request->getPost());
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                
                $service = $this->getServiceLocator()->get("service_faleconosco");
                $records = $request->getPost()->toArray();
                //$service->insert($records);
                $service->SendEmail($records);    
                return $this->redirect()->toRoute('home-message',array('msg_id'=>'13'));
            }
        }

            $me = $this->getServiceLocator()->get('ReverseOAuth2\Google');
            //$me = $this->getServiceLocator()->get('ReverseOAuth2\Github');
            //$me = $this->getServiceLocator()->get('ReverseOAuth2\Facebook');
            //$me = $this->getServiceLocator()->get('ReverseOAuth2\LinkedIn');

            if (strlen($this->params()->fromQuery('code')) > 10) {

                if($me->getToken($this->request)) {
                    $token = $me->getSessionToken(); // token in session
                } else {
                    $token = $me->getError(); // last returned error (array)
                }

                $info = $me->getInfo();

            } else {

                $url = $me->getUrl();

            }


        return new ViewModel(array('form' => $form,'token' => $token, 'info' => $info, 'url' => $url));
    }


}
