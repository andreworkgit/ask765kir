<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

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
    
    public function step2Action() {
        //return $this->redirect()->toRoute("home");
        
        $sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
        
		$area = $this->params()->fromRoute('area', 0);
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		$path_img = $path_folder."/myImgToCut.jpg";
		$records['area'] = $area; 
		list($records['width'], $records['height'], $records['type'], $records['attr']) = getimagesize($path_img);
        return new ViewModel(array('dados' => $records));
    }
	
	public function step3Action(){
		
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		$path_img = $path_folder."/myImgToCut.jpg";
		
		$area_sel = $this->params()->fromRoute('area-sel', 0);
		
		$coord = base64_decode(urldecode($area_sel));
    	$ar_coord = explode(",",$coord);
    
   	 	$area_size_default = 10;
    	list($width, $height) = getimagesize($path_img);
   	 	$image_p = imagecreatetruecolor($area_size_default,$area_size_default);
    	
		$image = imagecreatefromjpeg($path_img);
    	
		$name_submetida_100p = "10x10.jpg";
		
		imagecopy($image_p, $image, 0, 0, $ar_coord[0], $ar_coord[1], $width, $height);
    	imagejpeg($image_p, $path_folder."/".$name_submetida_100p, 100);
    	imagedestroy($image_p);
		
		
	}
	
	public function rmimgAction(){
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$area = $this->params()->fromRoute('area', 0);
		
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		$path_img = $path_folder."/myImgToCut.jpg";
		
		if(unlink($path_img)){
			return $this->redirect()->toRoute("area-edit",array('area'=>$area));
		}
		
	}
	
	
	
	public function myimgAction(){
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if($sessionLogin){
			$response = $this->getResponse();
        	$response->getHeaders()->addHeaderLine('Content-Type', "image/jpeg");
			
			$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
			
			$img_sel = $this->params()->fromRoute('img-sel', 0);
		
			
			switch($img_sel){
				case 1:
					$name_file = "10x10.jpg";
					break;
				default:
					$name_file = "myImgToCut.jpg";	
			}
			
			$imagegetcontent = @file_get_contents($path_folder."/".$name_file);
			$response->setStatusCode(200);
            $response->setContent($imagegetcontent);
				
			return $response;
		}
	}

	
	public function indexAction()
    {
    	
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$area = $this->params()->fromRoute('area', 0);
		
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		
		//verifica se diretorio exists
		if(!file_exists($path_folder))
		{
			$create_dir = mkdir($path_folder,0755,true);
			if(!$create_dir)
				return $this->redirect()->toRoute("home");
		}elseif(file_exists($path_folder."/myImgToCut.jpg")){
			return $this->redirect()->toRoute("area-edit",array('area'=>$area,'action'=>'step2'));
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
                $extension = new Extension(array("extension" => array("jpg", "png")));
                
                $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                $adapter->setValidators(array($size,$extension), $File['name']);
				
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
					
                    $form->setMessages(array('fileupload'=>$error ));
                } else {
                	
					
                	
                    $adapter->setDestination($path_folder);
					$adapter->addFilter('Rename', array('target' => $path_folder.'/myImgToCut.jpg',
                     'overwrite' => true));
                    if ($adapter->receive()) {
                        return $this->redirect()->toRoute("area-edit",array('area'=>$area,'action'=>'step2'));
                        //$profile->exchangeArray($form->getData());
                        //echo 'Profile Name '.$profile->profilename.' upload '.$profile->fileupload;
                    }
                }  
            }
        }
         
        return array('form' => $form);
    }

}
