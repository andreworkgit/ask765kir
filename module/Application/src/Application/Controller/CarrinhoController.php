<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class CarrinhoController extends AbstractActionController {
    
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
    	
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$session = new Container('carrinho');
		$areas = $session->offsetGet('areas');
		
		
		if(!empty($areas)){
			$records['vl_total'] = $session->offsetGet('vl_total');
		}else{
			return $this->redirect()->toRoute("home");
		}	
/*
        $referencia = $this->params()->fromRoute('ref', 0);
		$tipo 		= $this->params()->fromRoute('tipo', 0);
		$cod_msg 	= $this->params()->fromRoute('cod_msg', 0);

		$records[$tipo]['ref'] 	   = $this->params()->fromRoute('ref', 0);	
		$records[$tipo]['cod_msg'] = $this->params()->fromRoute('cod_msg', 0);

        $repository = $this->getEm()->getRepository("Application\Entity\Areas");
        $array_records = $repository->fetchPairs();
*/
        return new ViewModel(array('areas' => $areas,'records'=>$records));
    }
	
	

}
