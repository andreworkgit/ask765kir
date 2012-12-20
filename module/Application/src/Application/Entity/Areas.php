<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="area")
 * @ORM\Entity(repositoryClass="Application\Entity\AreasRepository")
 * 
 * @property int      $id
 * @property int      $id_user
 * @property int      $p_left
 * @property int      $p_top
 * @property int      $p_right
 * @property int      $p_btn
 * @property string   $titulo
 * @property string   $url 
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 */

class Areas {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
      /**
     * @ORM\Column(type="integer")
     */  
    protected $id_user;
        /**
        * @ORM\Column(type="integer")
        */
    protected $p_left;
        /**
        * @ORM\Column(type="integer")
        */
    protected $p_top;
        /**
        * @ORM\Column(type="integer")
        */
    protected $p_right;
        /**
        * @ORM\Column(type="integer")
        */
    protected $p_btn;
        /**
        * @ORM\Column(type="string")
        */
    protected $titulo;
    /**
        * @ORM\Column(type="string")
        */
    protected $url;
    /**
        * @ORM\Column(type="datetime")
        */
    protected $data_cadastro;
    /**
        * @ORM\Column(type="datetime")
        */
    protected $data_alteracao;
    
    
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id               = $data['id'];
        $this->id_user          = $data['id_user'];
        $this->p_left           = $data['p_left'];
        $this->p_top            = $data['p_top'];
        $this->p_right          = $data['p_right'];
        $this->p_btn            = $data['p_btn'];
        $this->titulo           = $data['titulo'];
        $this->url              = $data['url'];
        $date = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro    = $date;
        $this->data_alteracao   = $data['data_alteracao'];
  
    }
}
