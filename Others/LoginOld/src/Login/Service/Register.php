<?php

namespace Login\Service;
use Doctrine\ORM\EntityManager;

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface as ViewRenderer;
//use Zend\View\Renderer\PhpRenderer;


class Register {
  
    /**
     * @var EntityManager
     */
    protected $em;
    protected $entity;
    protected $emailRenderer;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function insert(array $data) {
        $entity = new \Login\Entity\Users();
        $entity->populate($data);
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    public function update(array $data) {
        $entity = $this->em->getReference('Login\Entity\Users', $data['id']);
        //$entity->nome = $data['nome'];
        //$entity->cep = $data['cep'];
        //seta elementos do $data para objs da entity
        
        foreach($data as $k => $v){
            $entity->$k = $v;
        }
        
        //var_dump($entity,$data);exit;
        //$entity = Configurator::configure($entity, $data);
        
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
    }
    
    public function SendEmail(Array $records){

        $options = new SmtpOptions( array(
        "name" => "gmail",
        "host" => "smtp.gmail.com",
        "port" => 587,
        "connection_class" => "plain",
        "connection_config" => array(   "username" => "urelby2@gmail.com",
                                        "password" => "a221b221",
                                        "ssl" => "tls" )
        ) );
        
        $mail = new Mail\Message();
        $mail->setFrom('urelby2@gmail.com', 'Pat3');
        $mail->addTo('andrework@gmail.com', 'Andre');
        //$mail->addCC( 'ao@gmail.com' );
        
        $mail->setSubject('1ª Etapa do cadastro realizada com sucesso');
        
        //$tpl = $this->getTemplateRenderer('login/register/confirmation-email',$dadosBody);
        $new_model = new ViewModel(array('dados'=>$records));
        $new_model->setTemplate('login/register/confirmation-email');
        $tpl = $this->emailRenderer->render($new_model);
        
        //$text = new MimePart($tpl);
        //$text->type = "text/plain";
        
        $html = new MimePart($tpl);
        $html->type = "text/html";
        
        //$image = new MimePart(fopen($pathToImage));
        //$image->type = "image/jpeg";
        
        $body = new MimeMessage();
        $body->setParts(array($html));
        
        $mail->setBody($body);
        
        $transport = new SmtpTransport();
        $transport->setOptions( $options );
        $transport->send($mail);
        
    }
    
    public function setMessageRenderer(ViewRenderer $emailRenderer)
    {
        $this->emailRenderer = $emailRenderer;
        return $this;
    }
    
    public function encryptPassword($password) {
        //colocar arquivo config
        $salt = "xhj28$83(*kdi#jjs";
        $hashSenha = hash('sha512', $password . $salt);
        for ($i = 0; $i < 64000; $i++)
            $hashSenha = hash('sha512', $hashSenha);
        
        return $hashSenha;
    }
    /*
    public function getTemplateRenderer($nameTemplate,Array $dados){

        //$resolver = $this->getResolver();
        $renderer = new PhpRenderer();
        //$renderer->setResolver($resolver);
        
        $new_model = new ViewModel(array('dados'=>$dados));
        $new_model->setTerminal(true)->setTemplate($nameTemplate);

        return $renderer->render($new_model);
    }*/
  
}