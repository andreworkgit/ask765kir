<?php

namespace Login\Auth;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result;

use Doctrine\ORM\EntityManager;

class Adapter implements AdapterInterface {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    protected $username;
    protected $password;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function authenticate() {
        $repository = $this->em->getRepository("Login\Entity\Users");
        $regs = $repository->findByEmail($this->getUsername());
        
        
        //var_dump($regs);
        if($regs->senha == '' and $regs->token != ''){
          return new Result(Result::FAILURE, array('user'=>$regs), array('Conta não confirmada'));
        }elseif( !empty($regs) && $regs->senha == $this->getPassword() ){
          return new Result(Result::SUCCESS, array('user'=>$regs),array('OK'));
        }else{
          return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array());
        }
          
    }

    
}
