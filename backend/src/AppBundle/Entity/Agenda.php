<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agenda
 *
 * @ORM\Table(name="agenda")
 * @ORM\Entity()
 */

class Agenda
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
     * @ORM\ManyToOne(targetEntity="Negocio", inversedBy="agenda")
     * @ORM\JoinColumn(name="negocio", referencedColumnName="id", nullable=false)
     **/
    private $negocio;    
  

    /**
     * @var string
     * @ORM\Column(name="cliente_mail", type="string", length=100, nullable=false)
     */
    private $clienteMail; 


    /**
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;    


    /**
     * @ORM\Column(name="fecha_agendado", type="datetime", nullable=false)
     */
    private $fechaAgendado;    


    /**
     * @var string
     * @ORM\Column(name="horario", type="string", length=5, nullable=false)
     */
    private $horario;     
             

    /**
     * @var boolean
     * @ORM\Column(name="confirmado", type="boolean", options={"default" : 1})
     */
    private $confirmado = true;


    /**
     * @var boolean
     * @ORM\Column(name="procesado", type="boolean", options={"default" : 0})
     */
    private $procesado = false;


    /**
     * @var boolean
     * @ORM\Column(name="no_concurre", type="boolean", options={"default" : 0})
     */
    private $noConcurre = false;        


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setFechaAgendado(new \DateTime("now")); 
        $this->setProcesado(false);  
        $this->setNoConcurre(false);   
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
     * Set clienteMail.
     *
     * @param string $clienteMail
     *
     * @return Agenda
     */
    public function setClienteMail($clienteMail)
    {
        $this->clienteMail = $clienteMail;

        return $this;
    }

    /**
     * Get clienteMail.
     *
     * @return string
     */
    public function getClienteMail()
    {
        return $this->clienteMail;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Agenda
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
     * Set fechaAgendado.
     *
     * @param \DateTime $fechaAgendado
     *
     * @return Agenda
     */
    public function setFechaAgendado($fechaAgendado)
    {
        $this->fechaAgendado = $fechaAgendado;

        return $this;
    }

    /**
     * Get fechaAgendado.
     *
     * @return \DateTime
     */
    public function getFechaAgendado()
    {
        return $this->fechaAgendado;
    }

    /**
     * Set horario.
     *
     * @param string $horario
     *
     * @return Agenda
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario.
     *
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set confirmado.
     *
     * @param bool $confirmado
     *
     * @return Agenda
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;

        return $this;
    }

    /**
     * Get confirmado.
     *
     * @return bool
     */
    public function getConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * Set procesado.
     *
     * @param bool $procesado
     *
     * @return Agenda
     */
    public function setProcesado($procesado)
    {
        $this->procesado = $procesado;

        return $this;
    }

    /**
     * Get procesado.
     *
     * @return bool
     */
    public function getProcesado()
    {
        return $this->procesado;
    }

    /**
     * Set noConcurre.
     *
     * @param bool $noConcurre
     *
     * @return Agenda
     */
    public function setNoConcurre($noConcurre)
    {
        $this->noConcurre = $noConcurre;

        return $this;
    }

    /**
     * Get noConcurre.
     *
     * @return bool
     */
    public function getNoConcurre()
    {
        return $this->noConcurre;
    }

    /**
     * Set negocio.
     *
     * @param \AppBundle\Entity\Negocio $negocio
     *
     * @return Agenda
     */
    public function setNegocio(\AppBundle\Entity\Negocio $negocio)
    {
        $this->negocio = $negocio;

        return $this;
    }

    /**
     * Get negocio.
     *
     * @return \AppBundle\Entity\Negocio
     */
    public function getNegocio()
    {
        return $this->negocio;
    }
}
