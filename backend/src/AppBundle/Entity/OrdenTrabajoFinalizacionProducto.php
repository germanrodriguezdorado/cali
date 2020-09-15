<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Finalizacion Producto
 *
 * @ORM\Table(name="orden_trabajo_finalizacion_producto")
 * @ORM\Entity()
 */

class OrdenTrabajoFinalizacionProducto
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
     * @ORM\ManyToOne(targetEntity="OrdenTrabajoFinalizacion", inversedBy="OrdenTrabajoFinalizacionProducto")
     * @ORM\JoinColumn(name="orden_trabajo_finalizacion", referencedColumnName="id", nullable=false)
     **/
    private $ordenTrabajoFinalizacion;


     /**
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="OrdenTrabajoLogProducto")
     * @ORM\JoinColumn(name="producto", referencedColumnName="id", nullable=false)
     **/
    private $producto;  


                                         



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
     * Set ordenTrabajoFinalizacion.
     *
     * @param \AppBundle\Entity\OrdenTrabajoFinalizacion $ordenTrabajoFinalizacion
     *
     * @return OrdenTrabajoFinalizacionProducto
     */
    public function setOrdenTrabajoFinalizacion(\AppBundle\Entity\OrdenTrabajoFinalizacion $ordenTrabajoFinalizacion)
    {
        $this->ordenTrabajoFinalizacion = $ordenTrabajoFinalizacion;

        return $this;
    }

    /**
     * Get ordenTrabajoFinalizacion.
     *
     * @return \AppBundle\Entity\OrdenTrabajoFinalizacion
     */
    public function getOrdenTrabajoFinalizacion()
    {
        return $this->ordenTrabajoFinalizacion;
    }

    /**
     * Set producto.
     *
     * @param \AppBundle\Entity\Producto $producto
     *
     * @return OrdenTrabajoFinalizacionProducto
     */
    public function setProducto(\AppBundle\Entity\Producto $producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto.
     *
     * @return \AppBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
