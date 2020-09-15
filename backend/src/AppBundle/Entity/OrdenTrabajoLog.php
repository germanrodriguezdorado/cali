<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Log
 *
 * @ORM\Table(name="orden_trabajo_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdenTrabajoLogRepository")
 */

class OrdenTrabajoLog
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
     * @ORM\Column(name="emisor", type="string", length=50, nullable=true)
     */
    private $emisor;          


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
     * @ORM\Column(name="estado", type="string", length=20, nullable=false)
     */
    private $estado;


    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_bolsones", type="string", length=10, nullable=true)
     */
    private $cantidadBolsones;


    /**
     * @var boolean
     *
     * @ORM\Column(name="balizas", type="boolean", nullable=false, options={"default" : 0})
     */
    private $balizas = false;   


    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="OrdenTrabajo")
     * @ORM\JoinColumn(name="movil_asignado", referencedColumnName="id", nullable=true)
     **/
    private $movilAsignado;                   




                                         


    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->setFecha(new \DateTime("now")); 
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
     * Set comentarios.
     *
     * @param string|null $comentarios
     *
     * @return OrdenTrabajoLog
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
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return OrdenTrabajoLog
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
     * Set estado.
     *
     * @param string $estado
     *
     * @return OrdenTrabajoLog
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set ordenTrabajo.
     *
     * @param \AppBundle\Entity\OrdenTrabajo $ordenTrabajo
     *
     * @return OrdenTrabajoLog
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
     * Set emisor.
     *
     * @param string|null $emisor
     *
     * @return OrdenTrabajoLog
     */
    public function setEmisor($emisor = null)
    {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * Get emisor.
     *
     * @return string|null
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    


    /**
     * Set cantidadBolsones.
     *
     * @param string|null $cantidadBolsones
     *
     * @return OrdenTrabajoLog
     */
    public function setCantidadBolsones($cantidadBolsones = null)
    {
        $this->cantidadBolsones = $cantidadBolsones;

        return $this;
    }

    /**
     * Get cantidadBolsones.
     *
     * @return string|null
     */
    public function getCantidadBolsones()
    {
        return $this->cantidadBolsones;
    }

    /**
     * Set balizas.
     *
     * @param bool $balizas
     *
     * @return OrdenTrabajoLog
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


    public function getBalizasPretty()
    {
        return $this->balizas ? "Si" : "No";
    }

    /**
     * Set movilAsignado.
     *
     * @param \AppBundle\Entity\Usuario|null $movilAsignado
     *
     * @return OrdenTrabajoLog
     */
    public function setMovilAsignado(\AppBundle\Entity\Usuario $movilAsignado = null)
    {
        $this->movilAsignado = $movilAsignado;

        return $this;
    }

    /**
     * Get movilAsignado.
     *
     * @return \AppBundle\Entity\Usuario|null
     */
    public function getMovilAsignado()
    {
        return $this->movilAsignado;
    }
}
