<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class CreditoController extends AbstractActionController {
    
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
		
        $request = $this->getRequest();
		$post = $request->getPost();
		$teste = array('nome'=>'pedro','nome2'=>'pedro 666');
		
		$str_post = implode("|", $post);
		
		file_put_contents("./data/files/logbcash.txt", $str_post, FILE_APPEND );
		
		$id_transacao 	= $post['id_transacao'];
		$status 		= $post['status'];
		$cod_status		= $post['cod_status'];
		$valor_original	= $post['valor_original'];
		$valor_loja		= $post['valor_loja'];
		$token 			= 'E868E43B48BA930F521174E59FC46EA7';
		
		$post_send = "transacao=$id_transacao" .
		"&status=$status" .
		"&cod_status=$cod_status" .
		"&valor_original=$valor_original" .
		"&valor_loja=$valor_loja" .
		"&token=$token";
		$enderecoPost = "https://www.bcash.com.br/checkout/verify/";
		
		ob_start();
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $enderecoPost);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_send);
		curl_exec ($ch);
		$resposta = ob_get_contents();
		ob_end_clean();
		file_put_contents("./data/files/logbcash.txt", "|RESPOSTA: ".$resposta, FILE_APPEND );
		if(trim($resposta)=="VERIFICADO"){
		
			$id_user = $post['produto_codigo_1'];
			$valor_credito = $post['produto_valor_1'];	
			$cod_status = $post['cod_status'];
			
			if($cod_status == 0)
			{
				$repository = $this->getEm()->getRepository("Application\Entity\Users");
				$obj_records = $repository->findById($id_user);
		
		        if(!empty($obj_records))
		        {
					$records_user['id'] = $obj_records->id;
					$records_user['credito'] = $obj_records->credito + $valor_credito;
					$service_user = $this->getServiceLocator()->get("service_register");
					$service_user->update($records_user);
				}
			}
				
		}
		
		exit;
    }
	
	

}
