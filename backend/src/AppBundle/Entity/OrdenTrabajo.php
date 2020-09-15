<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo
 *
 * @ORM\Table(name="orden_trabajo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdenTrabajoRepository")
 */

class OrdenTrabajo
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
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="OrdenTrabajo")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     **/
    private $usuario;


    /**
     * @ORM\ManyToOne(targetEntity="Zona", inversedBy="OrdenTrabajo")
     * @ORM\JoinColumn(name="zona", referencedColumnName="id", nullable=false)
     **/
    private $zona;


    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="OrdenTrabajo")
     * @ORM\JoinColumn(name="cliente", referencedColumnName="id", nullable=false)
     **/
    private $cliente;           


    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=100, nullable=true)
     */
    private $numero;    


    /**
     * @var string
     *
     * @ORM\Column(name="asignado", type="string", length=50, nullable=true)
     */
    private $asignado;                         


    /**
     * @var string
     *
     * @ORM\Column(name="prioridad", type="string", length=10, nullable=false)
     */
    private $prioridad;


    /**
     * @var string
     *
     * @ORM\Column(name="direccion_trabajo", type="string", length=200, nullable=true)
     */
    private $direccionTrabajo;    


    /**
     * @var string
     *
     * @ORM\Column(name="bolsones", type="string", length=5, nullable=true)
     */
    private $bolsones;    


    /**
     * @var string
     *
     * @ORM\Column(name="comentarios_iniciales", type="string", length=1000, nullable=true)
     */
    private $comentariosIniciales;    

    

    /**
     * @ORM\Column(name="fecha_solicitado", type="datetime", nullable=false)
     */
    private $fechaSolicitado; 

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=20, nullable=false)
     */
    private $estado;


    /**
     * @var string
     *
     * @ORM\Column(name="latitud", type="string", length=30, nullable=true)
     */
    private $latitud;    

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=30, nullable=true)
     */
    private $longitud;


    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="OrdenTrabajo")
     * @ORM\JoinColumn(name="movil_asignado", referencedColumnName="id", nullable=true)
     **/
    private $movilAsignado; 


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
        //$this->setFechaSolicitado(new \DateTime("now"));        
        $this->setEstado("Pendiente"); 
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
     * Set prioridad.
     *
     * @param string $prioridad
     *
     * @return OrdenTrabajo
     */
    public function setPrioridad($prioridad)
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    /**
     * Get prioridad.
     *
     * @return string
     */
    public function getPrioridad()
    {
        return $this->prioridad;
    }

    /**
     * Set direccionTrabajo.
     *
     * @param string|null $direccionTrabajo
     *
     * @return OrdenTrabajo
     */
    public function setDireccionTrabajo($direccionTrabajo = null)
    {
        $this->direccionTrabajo = $direccionTrabajo;

        return $this;
    }

    /**
     * Get direccionTrabajo.
     *
     * @return string|null
     */
    public function getDireccionTrabajo()
    {
        return $this->direccionTrabajo;
    }

    public function getDireccionSimple()
    {
        $array = explode(",", $this->direccionTrabajo);
        return $array[0];
    }

    /**
     * Set bolsones.
     *
     * @param string|null $bolsones
     *
     * @return OrdenTrabajo
     */
    public function setBolsones($bolsones = null)
    {
        $this->bolsones = $bolsones;

        return $this;
    }

    /**
     * Get bolsones.
     *
     * @return string|null
     */
    public function getBolsones()
    {
        return $this->bolsones;
    }

    

    /**
     * Set fechaSolicitado.
     *
     * @param \DateTime $fechaSolicitado
     *
     * @return OrdenTrabajo
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

    /**
     * Set estado.
     *
     * @param string $estado
     *
     * @return OrdenTrabajo
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
     * Set usuario.
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return OrdenTrabajo
     */
    public function setUsuario(\AppBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \AppBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set numero.
     *
     * @param string|null $numero
     *
     * @return OrdenTrabajo
     */
    public function setNumero($numero = null)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero.
     *
     * @return string|null
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set asignado.
     *
     * @param string|null $asignado
     *
     * @return OrdenTrabajo
     */
    public function setAsignado($asignado = null)
    {
        $this->asignado = $asignado;

        return $this;
    }

    /**
     * Get asignado.
     *
     * @return string|null
     */
    public function getAsignado()
    {
        return $this->asignado;
    }

    /**
     * Set latitud.
     *
     * @param string|null $latitud
     *
     * @return OrdenTrabajo
     */
    public function setLatitud($latitud = null)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud.
     *
     * @return string|null
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud.
     *
     * @param string|null $longitud
     *
     * @return OrdenTrabajo
     */
    public function setLongitud($longitud = null)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud.
     *
     * @return string|null
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set zona.
     *
     * @param \AppBundle\Entity\Zona $zona
     *
     * @return OrdenTrabajo
     */
    public function setZona(\AppBundle\Entity\Zona $zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona.
     *
     * @return \AppBundle\Entity\Zona
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set cliente.
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return OrdenTrabajo
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
     * Set comentariosIniciales.
     *
     * @param string|null $comentariosIniciales
     *
     * @return OrdenTrabajo
     */
    public function setComentariosIniciales($comentariosIniciales = null)
    {
        $this->comentariosIniciales = $comentariosIniciales;

        return $this;
    }

    /**
     * Get comentariosIniciales.
     *
     * @return string|null
     */
    public function getComentariosIniciales()
    {
        return $this->comentariosIniciales;
    }

    /**
     * Set movilAsignado.
     *
     * @param \AppBundle\Entity\Usuario|null $movilAsignado
     *
     * @return OrdenTrabajo
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

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return OrdenTrabajo
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
