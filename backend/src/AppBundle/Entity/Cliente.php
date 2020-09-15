<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClienteRepository")
 */

class Cliente
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
  

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre; 
             

    /**
     * @var string
     *
     * @ORM\Column(name="rut", type="string", length=12, nullable=false)
     */
    private $rut; 


    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default" : 1})
     */
    private $active = true;         


                                         


    /**
     * Constructor
     */
    public function __construct()
    {
       $this->setActive(true);
    }            



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Cliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    

    /**
     * Set rut.
     *
     * @param string $rut
     *
     * @return Cliente
     */
    public function setRut($rut)
    {
        $this->rut = $rut;

        return $this;
    }

    /**
     * Get rut.
     *
     * @return string
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Cliente
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }
}
