<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
    
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

        $referencia = $this->params()->fromRoute('ref', 0);
		$tipo 		= $this->params()->fromRoute('tipo', 0);
		$cod_msg 	= $this->params()->fromRoute('cod_msg', 0);

		$records[$tipo]['ref'] 	   = $this->params()->fromRoute('ref', 0);	
		$records[$tipo]['cod_msg'] = $this->params()->fromRoute('cod_msg', 0);
			
        /** Zend\DB
          $categoriaService = $this->getServiceLocator()->get("Livraria\Model\CategoriaService");
          $categorias = $categoriaService->fetchAll();
         */
        
        $repository = $this->getEm()->getRepository("Application\Entity\Areas");
        $array_records = $repository->fetchPairs();

        return new ViewModel(array('dados' => $records,'array_records_all' => $array_records));
    }
    
    public function loginIframeAction(){

		$new_model = new ViewModel();
		$new_model->setTerminal(true);
		$new_model->setTemplate('application/index/login-iframe');
        return $new_model;
	    
    }
    
    public function photoSpaceAction(){
		
		//$sessionLogin = $this->_helper->UserIdentity('Login');
		
		$tipo 		= $this->params()->fromRoute('tipo', 0);
		
		$background = imagecreatefromjpeg("./data/images/spacefree.jpg");
		//$response = $this->getResponse();
		
		if($tipo != 2){
		
			//$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
			$sm = $this->getEvent()->getApplication()->getServiceManager();
			$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
			$sessionLogin = $helper('Login');
			//var_dump($sessionLogin);exit;
			
			if(!empty($sessionLogin)){
			
				$repository = $this->getEm()->getRepository("Application\Entity\Areas");
				$obj_records = $repository->findByUser($sessionLogin['user']->id);
				
				$my_image = imagecreatefromjpeg("./public/images/app/m51_minhas.jpg");
	  			$imagesx = imagesx($my_image);
	  			$imagesy = imagesy($my_image);
				
				
				if(!empty($obj_records)){
					//$array_records = $obj_records->getArrayCopy();
					foreach($obj_records as $item){
						imagecopymerge($background, $my_image,$item->p_left,$item->p_top,0,0,$imagesx,$imagesy,100);	
					}
					
				}
				
			}
	        
	        //$response->getHeaders()->addHeaderLine('Content-Type', "image/jpg");
		
		}else{
			//$response->getHeaders()->addHeaderLine('Cache-Control',"no-cache, must-revalidate");
			header("Cache-Control: no-cache, must-revalidate");
		}
        //var_dump($response->getHeaders());
        
        header("Content-type: image/jpeg");
        
        imagejpeg($background,null,100);
        imagedestroy($background);
        
        return $response;
        
    }

}
