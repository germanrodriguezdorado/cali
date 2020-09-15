<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Log Foto
 *
 * @ORM\Table(name="orden_trabajo_log_foto")
 * @ORM\Entity()
 */

class OrdenTrabajoLogFoto
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
     * @ORM\ManyToOne(targetEntity="OrdenTrabajoLog", inversedBy="OrdenTrabajoLogFoto")
     * @ORM\JoinColumn(name="orden_trabajo_log", referencedColumnName="id", nullable=false)
     **/
    private $ordenTrabajoLog;


    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=50, nullable=false)
     */
    private $foto;    


                                         



    public function getFotoBase64()
    {
        if($this->foto != null){
            return "data:image/jpeg;base64,".base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/imagenes_ots/".$this->foto));      
        }else{
            return "";
        }
    }



  public function generate_thumbnail() {
    $location = $_SERVER["DOCUMENT_ROOT"]."/imagenes_ots/".$this->foto;
    $thumbnail = @exif_thumbnail($location);
    return 'data:image/jpeg;base64,'.base64_encode($thumbnail);
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
     * Set foto.
     *
     * @param string $foto
     *
     * @return OrdenTrabajoLogFoto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto.
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set ordenTrabajoLog.
     *
     * @param \AppBundle\Entity\OrdenTrabajoLog $ordenTrabajoLog
     *
     * @return OrdenTrabajoLogFoto
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
}
