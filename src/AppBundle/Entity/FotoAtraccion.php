<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="foto_atracciones")
 * @ORM\Entity()
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks
 */
class FotoAtraccion
{
    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var datetime $creado
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="creado", type="datetime")
     */
    private $creado;

    /**
     * @var datetime $actualizado
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="actualizado",type="datetime")
     */
    private $actualizado;

    /**
     * @var integer $creadoPor
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="creado_por", referencedColumnName="id", nullable=true)
     */
    private $creadoPor;

    /**
     * @var integer $actualizadoPor
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="actualizado_por", referencedColumnName="id", nullable=true)
     */
    private $actualizadoPor;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Atraccion", inversedBy="fotoAtraccion")
     * @ORM\JoinColumn(name="atraccion_id", referencedColumnName="id")
     */
    private $atraccion;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="ruta", type="string", nullable=true, length=255)
     */
    private $ruta;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="tipo", type="string", length=30)
     */
    private $tipo;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="descripcion",nullable=true, type="string", length=255)
     */
    private $descripcion;

    public function __toString()
    {
        return "";
    }

    public function getAbsolutePath()
    {
        return null === $this->ruta
            ? null
            : $this->getUploadRootDir() . $this->ruta;
    }

    public function getWebPath()
    {
        return null === $this->ruta
            ? null
            : $this->getUploadDir() . '/' . $this->ruta;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory ruta where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images/atraccion/';
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $foto;

    /**
     * Sets foto.
     *
     * @param UploadedFile $foto
     */
    public function setFoto(UploadedFile $foto = null)
    {
        $this->foto = $foto;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
            $this->ruta = null;
        } else {
            $this->ruta = 'initial';
        }
    }

    /**
     * Get foto.
     *
     * @return UploadedFile
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFoto()) {
            $this->ruta = $this->getFoto()->getClientOriginalName();
        }
    }

    /**
     * Called after entity persistence
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFoto()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }


        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFoto()->move(
            $this->getUploadRootDir(),
            $this->getFoto()->getClientOriginalName()
        );
        $this->setRuta($this->getFoto()->getClientOriginalName());

        // clean up the file property as you won't need it anymore
        $this->setFoto(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creado
     *
     * @param \DateTime $creado
     * @return FotoAgenda
     */
    public function setCreado($creado)
    {
        $this->creado = $creado;

        return $this;
    }

    /**
     * Get creado
     *
     * @return \DateTime
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * Set actualizado
     *
     * @param \DateTime $actualizado
     * @return FotoAgenda
     */
    public function setActualizado($actualizado)
    {
        $this->actualizado = $actualizado;

        return $this;
    }

    /**
     * Get actualizado
     *
     * @return \DateTime
     */
    public function getActualizado()
    {
        return $this->actualizado;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return FotoAgenda
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     * @return FotoAgenda
     */
    public function setCreadoPor(\UsuariosBundle\Entity\Usuario $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor
     *
     * @return \UsuariosBundle\Entity\Usuario
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $actualizadoPor
     * @return FotoAgenda
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor
     *
     * @return \UsuariosBundle\Entity\Usuario
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    /**
     * Set atraccion
     *
     * @param \AppBundle\Entity\Agenda $atraccion
     * @return FotoAgenda
     */
    public function setAtraccion(\AppBundle\Entity\Atraccion $atraccion = null)
    {
        $this->atraccion = $atraccion;

        return $this;
    }

    /**
     * Get atraccion
     *
     * @return \AppBundle\Entity\Atraccion
     */
    public function getAtraccion()
    {
        return $this->atraccion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return FotoAtraccion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return FotoAtraccion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
