<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")



  * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })

 
 */
class Usuario extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")

     */
    protected $id;  


    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=30, nullable=true)
     */
    private $tipo;


    /**
     * @var string
     *
     * @ORM\Column(name="perfil", type="string", length=30, nullable=true)
     */
    private $perfil;    



    /**
     * @ORM\ManyToOne(targetEntity="Zona", inversedBy="Usuario")
     * @ORM\JoinColumn(name="zona", referencedColumnName="id", nullable=true)
     **/
    private $zona;                     


    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;


    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=60, nullable=true)
     */
    private $apellido;


    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=60, nullable=true)
     */
    private $telefono;   


    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=160, nullable=true)
     */
    private $direccion;         


    /**
     * @var string
     *
     * @ORM\Column(name="movil_matricula", type="string", length=20, nullable=true)
     */
    private $movilMatricula;


    /**
     * @var string
     *
     * @ORM\Column(name="movil_modelo", type="string", length=50, nullable=true)
     */
    private $movilModelo;                                                             


    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;


    /**
     * @var boolean
     *
     * @ORM\Column(name="operativo", type="boolean", options={"default" : 1})
     */
    private $operativo = true;          

   

    public function getTipoCodificado()
    {
        if($this->tipo == "admin") return 1;
        if($this->tipo == "usuario_cliente") return 2;
        if($this->tipo == "movil") return 3;
    }


    public function getPerfilCodificado()
    {

        // Solo si es usuario cliente
        $respuesta = "";
        if($this->tipo == "usuario_cliente"){
            if($this->getPerfil() == "Administración") $respuesta = 1;
            if($this->getPerfil() == "Operaciones") $respuesta = 2;
            if($this->getPerfil() == "Programación") $respuesta = 3;
        }


       return $respuesta;
    }

    public function getNombrePretty()
    {
        return $this->nombre." ".$this->apellido;
    }

    /**
     * Set tipo.
     *
     * @param string|null $tipo
     *
     * @return Usuario
     */
    public function setTipo($tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string|null
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set perfil.
     *
     * @param string|null $perfil
     *
     * @return Usuario
     */
    public function setPerfil($perfil = null)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil.
     *
     * @return string|null
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set nombre.
     *
     * @param string|null $nombre
     *
     * @return Usuario
     */
    public function setNombre($nombre = null)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string|null
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido.
     *
     * @param string|null $apellido
     *
     * @return Usuario
     */
    public function setApellido($apellido = null)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido.
     *
     * @return string|null
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set telefono.
     *
     * @param string|null $telefono
     *
     * @return Usuario
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
     * Set direccion.
     *
     * @param string|null $direccion
     *
     * @return Usuario
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
     * Set active.
     *
     * @param bool $active
     *
     * @return Usuario
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

  


    public function getLastLoginPretty(){
        $respuesta = "";
        if($this->getLastLogin() != null){
            $respuesta = $this->getLastLogin()->format("d/m/Y H:i")." hs.";
        }
        return $respuesta;
    }

    public function getAlias(){
        return $this->getNombre()." ".$this->getApellido();
    }



    /**
     * Set zona.
     *
     * @param \AppBundle\Entity\Zona|null $zona
     *
     * @return Usuario
     */
    public function setZona(\AppBundle\Entity\Zona $zona = null)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona.
     *
     * @return \AppBundle\Entity\Zona|null
     */
    public function getZona()
    {
        return $this->zona;
    }


    /**
     * Set movilMatricula.
     *
     * @param string|null $movilMatricula
     *
     * @return Usuario
     */
    public function setMovilMatricula($movilMatricula = null)
    {
        $this->movilMatricula = $movilMatricula;

        return $this;
    }

    /**
     * Get movilMatricula.
     *
     * @return string|null
     */
    public function getMovilMatricula()
    {
        return $this->movilMatricula;
    }

    /**
     * Set movilModelo.
     *
     * @param string|null $movilModelo
     *
     * @return Usuario
     */
    public function setMovilModelo($movilModelo = null)
    {
        $this->movilModelo = $movilModelo;

        return $this;
    }

    /**
     * Get movilModelo.
     *
     * @return string|null
     */
    public function getMovilModelo()
    {
        return $this->movilModelo;
    }

    /**
     * Set operativo.
     *
     * @param bool $operativo
     *
     * @return Usuario
     */
    public function setOperativo($operativo)
    {
        $this->operativo = $operativo;

        return $this;
    }

    /**
     * Get operativo.
     *
     * @return bool
     */
    public function getOperativo()
    {
        return $this->operativo;
    }
}
