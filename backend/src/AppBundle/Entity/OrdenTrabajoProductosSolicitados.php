<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Productos Solicitados
 *
 * @ORM\Table(name="orden_trabajo_productos_solicitados")
 * @ORM\Entity()
 */

class OrdenTrabajoProductosSolicitados
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
     * @ORM\ManyToOne(targetEntity="OrdenTrabajo", inversedBy="OrdenTrabajoProductosSolicitados")
     * @ORM\JoinColumn(name="orden_trabajo", referencedColumnName="id", nullable=false)
     **/
    private $ordenTrabajo;


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
     * Set ordenTrabajo.
     *
     * @param \AppBundle\Entity\OrdenTrabajo $ordenTrabajo
     *
     * @return OrdenTrabajoProductosSolicitados
     */
    public function setOrdenTrabajo(\AppBundle\Entity\OrdenTrabajo $ordenTrabajo)
    {
        $this->ordenTrabajo = $ordenTrabajo;

        return $this;
    }

    /**
     * Get ordenTrabajo.
     *
     * @return \AppBundle\Entity\OrdenTrabajo
     */
    public function getOrdenTrabajo()
    {
        return $this->ordenTrabajo;
    }

    /**
     * Set producto.
     *
     * @param \AppBundle\Entity\Producto $producto
     *
     * @return OrdenTrabajoProductosSolicitados
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
