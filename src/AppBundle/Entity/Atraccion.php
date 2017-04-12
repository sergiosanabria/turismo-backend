<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Agenda
 *
 * @ORM\Table(name="atracciones")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AtraccionRepository")
 * @ExclusionPolicy("all")
 * @UniqueEntity("titulo")
 */
class Atraccion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SerializedName("id")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     * @SerializedName("titulo")
     * @Expose
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen", type="string", length=255, nullable=true)
     * @SerializedName("resumen")
     * @Expose
     */
    private $resumen;

    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo", type="text")
     * @SerializedName("cuerpo")
     * @Expose
     */
    private $cuerpo;


    /**
     * @var int
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @Serializer\Exclude()
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FotoAtraccion", mappedBy="atraccion", cascade={"persist", "remove"})
     */
    private $fotoAtraccion;

    /**
     * @var datetime $creado
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="creado", type="datetime")
     * @SerializedName("creado")
     * @Type("DateTime<'d-m-Y H:i'>")
     * @Expose
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategoriaAtraccion", inversedBy="atraccion")
     * @ORM\JoinColumn(name="categoria_atraccion_id", referencedColumnName="id", nullable=true)
     */
    private $categoriaAtraccion;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DireccionAtraccion", mappedBy="atraccion", cascade={"persist", "remove"})
     */
    private $direccion;

    /**
     * @SerializedName("archivos")
     * @VirtualProperty
     */
    public function getArchivos()
    {
        $archivos = array();
        $archivos['fotos'] = array();
        $archivos['audios'] = array();
        $archivos['videos'] = array();
        foreach ($this->fotoAtraccion as $item) {
            if ($item->getTipo() == 'imagen') {
                $archivos['fotos'] [] = $item;
            } elseif ($item->getTipo() == 'audio') {
                $archivos['audios'] [] = $item;
            } elseif ($item->getTipo() == 'video') {
                $archivos['videos'] [] = $item;
            }
        }

        return $archivos;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fotoAtraccion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->direccion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titulo
     *
     * @param string $titulo
     * @return Atraccion
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set resumen
     *
     * @param string $resumen
     * @return Atraccion
     */
    public function setResumen($resumen)
    {
        $this->resumen = $resumen;

        return $this;
    }

    /**
     * Get resumen
     *
     * @return string
     */
    public function getResumen()
    {
        return $this->resumen;
    }

    /**
     * Set cuerpo
     *
     * @param string $cuerpo
     * @return Atraccion
     */
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    /**
     * Get cuerpo
     *
     * @return string
     */
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     * @return Atraccion
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Atraccion
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set creado
     *
     * @param \DateTime $creado
     * @return Atraccion
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
     * @return Atraccion
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
     * Add fotoAtraccion
     *
     * @param \AppBundle\Entity\FotoAtraccion $fotoAtraccion
     * @return Atraccion
     */
    public function addFotoAtraccion(\AppBundle\Entity\FotoAtraccion $fotoAtraccion)
    {
        $this->fotoAtraccion[] = $fotoAtraccion;

        return $this;
    }

    /**
     * Remove fotoAtraccion
     *
     * @param \AppBundle\Entity\FotoAtraccion $fotoAtraccion
     */
    public function removeFotoAtraccion(\AppBundle\Entity\FotoAtraccion $fotoAtraccion)
    {
        $this->fotoAtraccion->removeElement($fotoAtraccion);
    }

    /**
     * Get fotoAtraccion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotoAtraccion()
    {
        return $this->fotoAtraccion;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     * @return Atraccion
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
     * @return Atraccion
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
     * Set categoriaAtraccion
     *
     * @param \AppBundle\Entity\CategoriaAtraccion $categoriaAtraccion
     * @return Atraccion
     */
    public function setCategoriaAtraccion(\AppBundle\Entity\CategoriaAtraccion $categoriaAtraccion = null)
    {
        $this->categoriaAtraccion = $categoriaAtraccion;

        return $this;
    }

    /**
     * Get categoriaAtraccion
     *
     * @return \AppBundle\Entity\CategoriaAtraccion
     */
    public function getCategoriaAtraccion()
    {
        return $this->categoriaAtraccion;
    }

    /**
     * Add direccion
     *
     * @param \AppBundle\Entity\DireccionAtraccion $direccion
     * @return Atraccion
     */
    public function addDireccion(\AppBundle\Entity\DireccionAtraccion $direccion)
    {
        $this->direccion[] = $direccion;

        return $this;
    }

    /**
     * Remove direccion
     *
     * @param \AppBundle\Entity\DireccionAtraccion $direccion
     */
    public function removeDireccion(\AppBundle\Entity\DireccionAtraccion $direccion)
    {
        $this->direccion->removeElement($direccion);
    }

    /**
     * Get direccion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDireccion()
    {
        return $this->direccion;
    }
}
