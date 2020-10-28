<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Negocio
 *
 * @ORM\Table(name="negocio")
 * @ORM\Entity()
 */

class Negocio
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre; 


    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=100, nullable=false)
     */
    private $slug;     
             

    /**
     * @var string
     * @ORM\Column(name="direccion", type="string", length=100, nullable=false)
     */
    private $direccion;


    /**
     * @var string
     * @ORM\Column(name="barrio", type="string", length=40, nullable=false)
     */
    private $barrio;


    /**
     * @var string
     * @ORM\Column(name="telefono", type="string", length=40, nullable=true)
     */
    private $telefono; 


    /**
     * @var string
     * @ORM\Column(name="duracion", type="string", length=40, nullable=true, columnDefinition="enum('10','15','30','60','120')")
     */
    private $duracion;           

    /**
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     **/
    private $usuario;

    /**
     * @var boolean
     * @ORM\Column(name="lunes", type="boolean", options={"default" : 0})
     */
    private $lunes = false;

    /**
     * @var boolean
     * @ORM\Column(name="martes", type="boolean", options={"default" : 0})
     */
    private $martes = false;

    /**
     * @var boolean
     * @ORM\Column(name="miercoles", type="boolean", options={"default" : 0})
     */
    private $miercoles = false;

    /**
     * @var boolean
     * @ORM\Column(name="jueves", type="boolean", options={"default" : 0})
     */
    private $jueves = false;

    /**
     * @var boolean
     * @ORM\Column(name="viernes", type="boolean", options={"default" : 0})
     */
    private $viernes = false;

    /**
     * @var boolean
     * @ORM\Column(name="sabado", type="boolean", options={"default" : 0})
     */
    private $sabado = false;

    /**
     * @var boolean
     * @ORM\Column(name="domingo", type="boolean", options={"default" : 0})
     */
    private $domingo = false;

    /**
     * @var string
     * @ORM\Column(name="desde", type="string", length=5, nullable=true)
     */
    private $desde;

    /**
     * @var string
     * @ORM\Column(name="hasta", type="string", length=5, nullable=true)
     */
    private $hasta;

    /**
     * @var string
     * @ORM\Column(name="descanso", type="string", length=20, nullable=true)
     */
    private $descanso;                                                 

                     


    /**
     * Constructor
     */
    public function __construct()
    {
       
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
     * @return Negocio
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
     * Set direccion.
     *
     * @param string $direccion
     *
     * @return Negocio
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set barrio.
     *
     * @param string $barrio
     *
     * @return Negocio
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio.
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set telefono.
     *
     * @param string|null $telefono
     *
     * @return Negocio
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

    /**
     * Set duracion.
     *
     * @param string|null $duracion
     *
     * @return Negocio
     */
    public function setDuracion($duracion = null)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion.
     *
     * @return string|null
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Set usuario.
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return Negocio
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
     * Set lunes.
     *
     * @param bool $lunes
     *
     * @return Negocio
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes.
     *
     * @return bool
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes.
     *
     * @param bool $martes
     *
     * @return Negocio
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes.
     *
     * @return bool
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles.
     *
     * @param bool $miercoles
     *
     * @return Negocio
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles.
     *
     * @return bool
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves.
     *
     * @param bool $jueves
     *
     * @return Negocio
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves.
     *
     * @return bool
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set viernes.
     *
     * @param bool $viernes
     *
     * @return Negocio
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes.
     *
     * @return bool
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado.
     *
     * @param bool $sabado
     *
     * @return Negocio
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado.
     *
     * @return bool
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo.
     *
     * @param bool $domingo
     *
     * @return Negocio
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo.
     *
     * @return bool
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set desde.
     *
     * @param string|null $desde
     *
     * @return Negocio
     */
    public function setDesde($desde = null)
    {
        $this->desde = $desde;

        return $this;
    }

    /**
     * Get desde.
     *
     * @return string|null
     */
    public function getDesde()
    {
        return $this->desde;
    }

    /**
     * Set hasta.
     *
     * @param string|null $hasta
     *
     * @return Negocio
     */
    public function setHasta($hasta = null)
    {
        $this->hasta = $hasta;

        return $this;
    }

    /**
     * Get hasta.
     *
     * @return string|null
     */
    public function getHasta()
    {
        return $this->hasta;
    }

    /**
     * Set descanso.
     *
     * @param string|null $descanso
     *
     * @return Negocio
     */
    public function setDescanso($descanso = null)
    {
        $this->descanso = $descanso;

        return $this;
    }


    /**
     * Get descanso.
     *
     * @return string|null
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

   

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Negocio
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
