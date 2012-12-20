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
              $array[$seq_coord]['url'] = "http://".$entity->url;   
            }
        }
        
        
        return $array;
    }
    
}
