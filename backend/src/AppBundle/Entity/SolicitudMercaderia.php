<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SolicitudMercaderia
 *
 * @ORM\Table(name="solicitud_mercaderia")
 * @ORM\Entity()
 */

class SolicitudMercaderia
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
     * @ORM\Column(name="rut", type="string", length=25, nullable=false)
     */
    private $rut;       


    /**
     * @var string
     *
     * @ORM\Column(name="producto", type="string", length=40, nullable=false)
     */
    private $producto;      

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="string", length=10, nullable=false)
     */
    private $cantidad;              


    /**
     * @ORM\Column(name="fecha_solicitado", type="datetime", nullable=false)
     */
    private $fechaSolicitado; 

                                         
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setFechaSolicitado(new \DateTime("now"));        
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
     * Set rut.
     *
     * @param string $rut
     *
     * @return SolicitudMercaderia
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
     * Set producto.
     *
     * @param string $producto
     *
     * @return SolicitudMercaderia
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto.
     *
     * @return string
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set cantidad.
     *
     * @param string $cantidad
     *
     * @return SolicitudMercaderia
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad.
     *
     * @return string
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fechaSolicitado.
     *
     * @param \DateTime $fechaSolicitado
     *
     * @return SolicitudMercaderia
     */
    public function setFechaSolicitado($fechaSolicitado)
    {
        $this->fechaSolicitado = $fechaSolicitado;

        return $this;
    }

    /**
     * Get fechaSolicitado.
     *
     * @return \DateTime
     */
    public function getFechaSolicitado()
    {
        return $this->fechaSolicitado;
    }
}
