<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
    
    public function indexAction() {
        //echo "aki";exit;
        $records['nome']="teste";
		$array_records['tipo']="teste2";
		
        return new ViewModel(array('dados' => $records,'array_records_all' => $array_records));
    }

}
