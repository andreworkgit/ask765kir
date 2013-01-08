<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Validator\File\Size;

class AreaController extends AbstractActionController {
    
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
    /*
    public function indexAction() {
        return $this->redirect()->toRoute("home");
        //return new ViewModel(array('dados' => $records,'array_records_all' => $array_records));
    }*/

	
	public function indexAction()
    {
    	
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		
		
		//var_dump($sessionLogin['user']->diretorio);
    	
        $form = $this->getServiceLocator()->get("service_area_upload_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            //$profile = new Profile();
            //$form->setInputFilter($profile->getInputFilter());
            
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileupload');
            $data = array_merge(
                 $nonFile,
                 array('fileupload'=> $File['name'])
             );
			 
			// var_dump($data);exit;
			 
			 
            //set data post and file ...    
            $form->setData($data);
             
            if ($form->isValid()) {
                
                $size = new Size(array('min'=>5120,'max'=>512000)); //minimum bytes filesize
                
                $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                $adapter->setValidators(array($size), $File['name']);
				
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
					
                    $form->setMessages(array('fileupload'=>$error ));
                } else {
                	
					$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
					//verifica se diretorio exists
					if(!file_exists($path_folder))
					{
						$create_dir = mkdir($path_folder,0755,true);
						if(!$create_dir)
							return $this->redirect()->toRoute("home");
					}
                	
                    $adapter->setDestination($path_folder);
					$adapter->addFilter('Rename', array('target' => $path_folder.'/myImgToCut.jpg',
                     'overwrite' => true));
                    if ($adapter->receive()) {
                        echo "Enviado com sucesso";	
                        //$profile->exchangeArray($form->getData());
                        //echo 'Profile Name '.$profile->profilename.' upload '.$profile->fileupload;
                    }
                }  
            }
        }
         
        return array('form' => $form);
    }

}
