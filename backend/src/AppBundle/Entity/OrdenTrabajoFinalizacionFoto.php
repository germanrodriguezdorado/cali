<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden de trabajo Finalizacion Foto
 *
 * @ORM\Table(name="orden_trabajo_finalizacion_foto")
 * @ORM\Entity()
 */

class OrdenTrabajoFinalizacionFoto
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
     * @ORM\ManyToOne(targetEntity="OrdenTrabajoFinalizacion", inversedBy="OrdenTrabajoFinalizacionFoto")
     * @ORM\JoinColumn(name="orden_trabajo_finalizacion", referencedColumnName="id", nullable=false)
     **/
    private $ordenTrabajoFinalizacion;


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
    //return $location;
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
     * @return OrdenTrabajoFinalizacionFoto
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
     * Set ordenTrabajoFinalizacion.
     *
     * @param \AppBundle\Entity\OrdenTrabajoFinalizacion $ordenTrabajoFinalizacion
     *
     * @return OrdenTrabajoFinalizacionFoto
     */
    public function setOrdenTrabajoFinalizacion(\AppBundle\Entity\OrdenTrabajoFinalizacion $ordenTrabajoFinalizacion)
    {
        $this->ordenTrabajoFinalizacion = $ordenTrabajoFinalizacion;

        return $this;
    }

    /**
     * Get ordenTrabajoFinalizacion.
     *
     * @return \AppBundle\Entity\OrdenTrabajoFinalizacion
     */
    public function getOrdenTrabajoFinalizacion()
    {
        return $this->ordenTrabajoFinalizacion;
    }
}
