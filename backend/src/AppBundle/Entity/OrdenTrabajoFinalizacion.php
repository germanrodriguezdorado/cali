<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Finalizacion
 *
 * @ORM\Table(name="orden_trabajo_finalizacion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdenTrabajoFinalizacionRepository")
 */

class OrdenTrabajoFinalizacion
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
     * @ORM\ManyToOne(targetEntity="OrdenTrabajo", inversedBy="OrdenTrabajoLog")
     * @ORM\JoinColumn(name="orden_trabajo", referencedColumnName="id", nullable=false)
     **/
    private $ordenTrabajo;
   

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_bolsones", type="string", length=10, nullable=true)
     */
    private $cantidadBolsones;   


    /**
     * @var string
     *
     * @ORM\Column(name="comentarios", type="string", length=1000, nullable=true)
     */
    private $comentarios;      


    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;    


    /**
     * @var string
     *
     * @ORM\Column(name="fecha_manual", type="string", length=100, nullable=true)
     */
    private $fechaManual;       


    /**
     * @var boolean
     *
     * @ORM\Column(name="balizas", type="boolean", nullable=false, options={"default" : 0})
     */
    private $balizas = false; 


    /**
     * @var string
     *
     * @ORM\Column(name="material_utilizado", type="string", length=100, nullable=false)
     */
    private $materialUtilizado;         



                                         


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setFecha(new \DateTime("now")); 
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
     * Set cantidadBolsones.
     *
     * @param string $cantidadBolsones
     *
     * @return OrdenTrabajoFinalizacion
     */
    public function setCantidadBolsones($cantidadBolsones)
    {
        $this->cantidadBolsones = $cantidadBolsones;

        return $this;
    }

    /**
     * Get cantidadBolsones.
     *
     * @return string
     */
    public function getCantidadBolsones()
    {
        return $this->cantidadBolsones;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return OrdenTrabajoFinalizacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set ordenTrabajo.
     *
     * @param \AppBundle\Entity\OrdenTrabajo $ordenTrabajo
     *
     * @return OrdenTrabajoFinalizacion
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
     * Set comentarios.
     *
     * @param string|null $comentarios
     *
     * @return OrdenTrabajoFinalizacion
     */
    public function setComentarios($comentarios = null)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios.
     *
     * @return string|null
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

   



    /**
     * Set fechaManual.
     *
     * @param string|null $fechaManual
     *
     * @return OrdenTrabajoFinalizacion
     */
    public function setFechaManual($fechaManual = null)
    {
        $this->fechaManual = $fechaManual;

        return $this;
    }

    /**
     * Get fechaManual.
     *
     * @return string|null
     */
    public function getFechaManual()
    {
        return $this->fechaManual;
    }

    /**
     * Set balizas.
     *
     * @param bool $balizas
     *
     * @return OrdenTrabajoFinalizacion
     */
    public function setBalizas($balizas)
    {
        $this->balizas = $balizas;

        return $this;
    }

    /**
     * Get balizas.
     *
     * @return bool
     */
    public function getBalizas()
    {
        return $this->balizas;
    }

    /**
     * Set materialUtilizado.
     *
     * @param string $materialUtilizado
     *
     * @return OrdenTrabajoFinalizacion
     */
    public function setMaterialUtilizado($materialUtilizado)
    {
        $this->materialUtilizado = $materialUtilizado;

        return $this;
    }

    /**
     * Get materialUtilizado.
     *
     * @return string
     */
    public function getMaterialUtilizado()
    {
        return $this->materialUtilizado;
    }


    public function getBalizasPretty()
    {
        return $this->balizas ? "Si" : "No";
    }
}
