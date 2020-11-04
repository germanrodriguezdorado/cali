<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bloqueo
 *
 * @ORM\Table(name="bloqueo")
 * @ORM\Entity()
 */

class Bloqueo
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
     * @ORM\ManyToOne(targetEntity="Negocio", inversedBy="bloqueo")
     * @ORM\JoinColumn(name="negocio", referencedColumnName="id", nullable=false)
     **/
    private $negocio;    
  

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email; 


    
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
     * Set email.
     *
     * @param string $email
     *
     * @return Bloqueo
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set negocio.
     *
     * @param \AppBundle\Entity\Negocio $negocio
     *
     * @return Bloqueo
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
