<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zona
 *
 * @ORM\Table(name="zona")
 * @ORM\Entity()
 */

class Zona
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
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="Zona")
     * @ORM\JoinColumn(name="cliente", referencedColumnName="id", nullable=false)
     **/
    private $cliente;   


    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=100, nullable=true)
     */
    private $direccion;     


    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=100, nullable=true)
     */
    private $telefono;     


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
     * @return Zona
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
     * Set cliente.
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Zona
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente.
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Zona
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

    /**
     * Set direccion.
     *
     * @param string|null $direccion
     *
     * @return Zona
     */
    public function setDireccion($direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string|null
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono.
     *
     * @param string|null $telefono
     *
     * @return Zona
     */
    public function setTelefono($telefono = null)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return string|null
     */
    public function getTelefono()
    {
        return $this->telefono;
    }
}
