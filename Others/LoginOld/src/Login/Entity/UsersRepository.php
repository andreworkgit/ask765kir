<?php

namespace Login\Entity;

use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository {


  public function findByToken($token){
    
    $records_users = $this->findOneByToken($token);
    return $records_users;
  }
  
  public function findByEmail($email){
    
    $records_users = $this->findOneByEmail($email);
    return $records_users;
  }
  
/*
    public function fetchPairs() {
        $entities = $this->findAll();
        $array = array();
        
        foreach($entities as $entity) {
            $array[$entity->getId()] = $entity->getNome();
        }
        
        return $array;
    }
    */
}
