<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

class AreasRepository extends EntityRepository {

    public function fetchPairs() {
        $entities = $this->findAll();

        if(!empty($entities))
        {
            $array = array();
            foreach($entities as $entity){
              $seq_coord = $entity->p_left.$entity->p_top.$entity->p_right.$entity->p_btn;
              $array[$seq_coord]['seq_coord'] = $seq_coord;
              $array[$seq_coord]['titulo'] = $entity->titulo;
              $array[$seq_coord]['url'] = $entity->url;
			  $array[$seq_coord]['id_user'] = $entity->id_user;   
            }
        }
        
        
        return $array;
    }
	
	public function countAll(){
		
		$entities = $this->findAll();
		return count($entities);
	}
	
	public function findByUser($userId)
	{ 
		$records = $this->findBy(array('id_user'=>$userId));
		return $records;
	}
	
	public function findByArea($p_left,$p_top,$p_right,$p_btn)
	{ 
		$records = $this->findOneBy(array('p_left' => $p_left,'p_top' => $p_top,'p_right' => $p_right,'p_btn' =>$p_btn));
		return $records;
	}
    
}
