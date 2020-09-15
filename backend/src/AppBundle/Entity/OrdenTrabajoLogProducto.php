<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Log Producto
 *
 * @ORM\Table(name="orden_trabajo_log_producto")
 * @ORM\Entity()
 */

class OrdenTrabajoLogProducto
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
     * @ORM\ManyToOne(targetEntity="OrdenTrabajoLog", inversedBy="OrdenTrabajoLogProducto")
     * @ORM\JoinColumn(name="orden_trabajo_log", referencedColumnName="id", nullable=false)
     **/
    private $ordenTrabajoLog;


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
     * Set ordenTrabajoLog.
     *
     * @param \AppBundle\Entity\OrdenTrabajoLog $ordenTrabajoLog
     *
     * @return OrdenTrabajoLogProducto
     */
    public function setOrdenTrabajoLog(\AppBundle\Entity\OrdenTrabajoLog $ordenTrabajoLog)
    {
        $this->ordenTrabajoLog = $ordenTrabajoLog;

        return $this;
    }

    /**
     * Get ordenTrabajoLog.
     *
     * @return \AppBundle\Entity\OrdenTrabajoLog
     */
    public function getOrdenTrabajoLog()
    {
        return $this->ordenTrabajoLog;
    }

    /**
     * Set producto.
     *
     * @param \AppBundle\Entity\Producto $producto
     *
     * @return OrdenTrabajoLogProducto
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
