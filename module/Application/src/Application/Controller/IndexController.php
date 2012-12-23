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
        /*
            $repository = $this->getEm()->getRepository("Application\Entity\Areas");
            $array_records = $repository->fetchPairs();
            //precisa seta layout principal
            return new ViewModel(array('array_records_all' => $array_records));
        */
        
        return new ViewModel(array('dados' => $records));
    }
    
    public function loginIframeAction(){

		$new_model = new ViewModel();
		$new_model->setTerminal(true);
		$new_model->setTemplate('application/index/login-iframe');
        return $new_model;
	    
    }
    
    public function photoSpaceAction(){
        $response = $this->getResponse();
        
        $response->getHeaders()->addHeaderLine('Content-Type', "image/jpg");
        //var_dump($response->getHeaders());
        $background = imagecreatefromjpeg("./data/images/spacefree.jpg");
        //header("Content-type: image/jpeg");
        imagejpeg($background,null,100);
        imagedestroy($background);
        
        return $response;
        
    }

}
