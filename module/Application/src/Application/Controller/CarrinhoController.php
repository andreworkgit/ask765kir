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
	
	public function finishAction(){
		
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		var_dump($sessionLogin);exit;
		
		$background = imagecreatefromjpeg("./data/images/spacefree.jpg");
    	$my_image = imagecreatefromjpeg("./public/images/app/m51_comprado.jpg");
    	$imagesx = imagesx($my_image);
    	$imagesy = imagesy($my_image);
		
		$session = new Container('carrinho');
		$areas = $session->offsetGet('areas');
		$qtd = $session->offsetGet('qtd');
		$vl_total = $session->offsetGet('vl_total');
		
		if(!empty($areas)){
			$soma_insert = 0;
			$service = $this->getServiceLocator()->get("service_carrinho");
			foreach($areas as $k => $v){
				$v_coord = explode(",",$v['coord']);
        		$records = array('id_user'=>$sessionLogin['user']->id,'p_left'=>$v_coord[0],'p_top'=>$v_coord[1],'p_right'=>$v_coord[2],'p_btn'=>$v_coord[3]);
      			$service->insert($records);
				imagecopymerge($background, $my_image,$v_coord[0],$v_coord[1],0,0,$imagesx,$imagesy,100);
          		$soma_insert++;
			}
			
			if($soma_insert == $qtd){
				
			}
		}
		
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