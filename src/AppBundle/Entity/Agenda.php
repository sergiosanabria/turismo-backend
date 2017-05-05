<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Agenda
 *
 * @ORM\Table(name="agendas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaRepository")
 * @ExclusionPolicy("all")
 */
class Agenda
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
     * @ORM\Column(name="titulo", type="string", length=255, unique=true)
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
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="cuerpo", type="text", nullable=true)
     * @SerializedName("cuerpo")
     * @Expose
     */
    private $cuerpo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visible_desde", type="datetime", nullable=true)
     */
    private $visibleDesde;

    /**
     * @var string
     *
     * @ORM\Column(name="visible_hasta", type="datetime", nullable=true)
     */
    private $visibleHasta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_evento_desde", type="datetime")
     * @SerializedName("fecha_evento_desde")
     * @Type("DateTime<'d-m-Y \a \l\a\s H:i'>")
     * @Expose
     */
    private $fechaEventoDesde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_evento_hasta", type="datetime")
     * @SerializedName("fecha_evento_hasta")
     * @Type("DateTime<'d-m-Y \a \l\a\s H:i'>")
     * @Expose
     */
    private $fechaEventoHasta;

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
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FotoAgenda", mappedBy="agenda", cascade={"persist", "remove"})
     */
    private $fotoAgenda;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategoriaAgenda", inversedBy="agenda")
     * @ORM\JoinColumn(name="categoria_agenda_id", referencedColumnName="id", nullable=true)
     */
    private $categoriaAgenda;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DireccionAgenda", mappedBy="agenda", cascade={"persist", "remove"})
     */
    private $direccion;

    public function __toString()
    {
//        return $this->getTitulo();
        return "AAS";
    }

    /**
     * @SerializedName("categoria")
     * @VirtualProperty
     */
    public function getCategoria()
    {
        return $this->getCategoriaAgenda() ? $this->getCategoriaAgenda()->getNombre() : null;
    }

    /**
     * @SerializedName("icono")
     * @VirtualProperty
     */
    public function getIcono()
    {
        return $this->getCategoriaAgenda() ? $this->getCategoriaAgenda()->getIcono() : null;
    }


    /**
     * @SerializedName("fecha_evento_rango")
     * @VirtualProperty
     */
    public function getFechaEventoRango()
    {
        return array(
            'desde' => $this->fechaEventoDesde ? $this->fechaEventoDesde->format('Y-m-d') . ' 00:00:00' : false,
            'hasta' => $this->fechaEventoHasta ? $this->fechaEventoHasta->format('Y-m-d') . ' 23:59:59' : false,
        );
    }

    /**
     * @SerializedName("fecha_rango")
     * @VirtualProperty
     */
    public function getFechaVisibleRango()
    {
        return array(
            'desde' => $this->visibleDesde ? $this->visibleDesde->format('Y-m-d') . ' 00:00:00' : false,
            'hasta' => $this->visibleHasta ? $this->visibleHasta->format('Y-m-d') . ' 23:59:59' : false,
        );
    }

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
        foreach ($this->fotoAgenda as $item) {
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
     * @SerializedName("direccion_mapa")
     * @VirtualProperty
     */
    public function getDireccionMapa()
    {

        if ($this->getDireccion()->count() > 0 && $this->getDireccion()->first()->getLocalidad()) {

            $altura = $this->getDireccion()->first()->getAltura() ? $this->getDireccion()->first()->getAltura() . ', ' : '';
            $calle = $this->getDireccion()->first()->getCalle() ? $this->getDireccion()->first()->getCalle() . ' ' : '';
            return
                $calle . $altura .
                $this->getDireccion()->first()->getLocalidad() . ', ' .
                $this->getDireccion()->first()->getLocalidad()->getDepartamento()->getProvincia();

        }

        return null;
    }

    /**
     * @SerializedName("imagen_principal")
     * @VirtualProperty
     */
    public function getImagenPrincipal()
    {

        if ($this->getFotoAgenda()->count() > 0) {

            return $this->getFotoAgenda()->first()->getUploadDir() . $this->getFotoAgenda()->first()->getRuta();
        }

        return null;

    }

    /**
     * @SerializedName("direccion")
     * @VirtualProperty
     */
    public function getDireccionAgenda()
    {

        if ($this->getDireccion()->count() > 0 && $this->getDireccion()->first()->getLocalidad()) {

            return $this->getDireccion()->first()->getLocalidad()->getDepartamento()->getProvincia() . ', ' .
                $this->getDireccion()->first()->getLocalidad() . ', ' .
                $this->getDireccion()->first()->getCalle() . ' ' .
                $this->getDireccion()->first()->getAltura();

        }

        return null;
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
     *
     * @return Agenda
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
     *
     * @return Agenda
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
     *
     * @return Agenda
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
     * Set visibleDesde
     *
     * @param \DateTime $visibleDesde
     *
     * @return Agenda
     */
    public function setVisibleDesde($visibleDesde)
    {
        $this->visibleDesde = $visibleDesde;

        return $this;
    }

    /**
     * Get visibleDesde
     *
     * @return \DateTime
     */
    public function getVisibleDesde()
    {
        return $this->visibleDesde;
    }

    /**
     * Set visibleHasta
     *
     * @param string $visibleHasta
     *
     * @return Agenda
     */
    public function setVisibleHasta($visibleHasta)
    {
        $this->visibleHasta = $visibleHasta;

        return $this;
    }

    /**
     * Get visibleHasta
     *
     * @return string
     */
    public function getVisibleHasta()
    {
        return $this->visibleHasta;
    }

    /**
     * Set fechaEventoDesde
     *
     * @param \DateTime $fechaEventoDesde
     *
     * @return Agenda
     */
    public function setFechaEventoDesde($fechaEventoDesde)
    {
        $this->fechaEventoDesde = $fechaEventoDesde;

        return $this;
    }

    /**
     * Get fechaEventoDesde
     *
     * @return \DateTime
     */
    public function getFechaEventoDesde()
    {
        return $this->fechaEventoDesde;
    }

    /**
     * Set fechaEventoHasta
     *
     * @param \DateTime $fechaEventoHasta
     *
     * @return Agenda
     */
    public function setFechaEventoHasta($fechaEventoHasta)
    {
        $this->fechaEventoHasta = $fechaEventoHasta;

        return $this;
    }

    /**
     * Get fechaEventoHasta
     *
     * @return \DateTime
     */
    public function getFechaEventoHasta()
    {
        return $this->fechaEventoHasta;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Agenda
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
     *
     * @return Agenda
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
     * Constructor
     */
    public function __construct()
    {
        $this->fotoAgenda = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set creado
     *
     * @param \DateTime $creado
     *
     * @return Agenda
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
     *
     * @return Agenda
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
     * Add fotoAgenda
     *
     * @param \AppBundle\Entity\FotoAgenda $fotoAgenda
     *
     * @return Agenda
     */
    public function addFotoAgenda(\AppBundle\Entity\FotoAgenda $fotoAgenda)
    {
        $this->fotoAgenda[] = $fotoAgenda;

        return $this;
    }

    /**
     * Remove fotoAgenda
     *
     * @param \AppBundle\Entity\FotoAgenda $fotoAgenda
     */
    public function removeFotoAgenda(\AppBundle\Entity\FotoAgenda $fotoAgenda)
    {
        $this->fotoAgenda->removeElement($fotoAgenda);
    }

    /**
     * Get fotoAgenda
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotoAgenda()
    {
        return $this->fotoAgenda;
    }

    /**
     * Add fotoAgenda
     *
     * @param \AppBundle\Entity\FotoAgenda $fotoAgenda
     *
     * @return Agenda
     */
    public function addFotoAgendon(\AppBundle\Entity\FotoAgenda $fotoAgenda)
    {
        $fotoAgenda->setAgenda($this);
        $this->fotoAgenda->add($fotoAgenda);
        return $this;
    }

    /**
     * Remove fotoAgenda
     *
     * @param \AppBundle\Entity\FotoAgenda $fotoAgenda
     */
    public function removeFotoAgendon(\AppBundle\Entity\FotoAgenda $fotoAgenda)
    {
        $this->fotoAgenda->removeElement($fotoAgenda);
    }

    /**
     * Get fotoAgenda
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotoAgendon()
    {
        return $this->fotoAgenda;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     *
     * @return Agenda
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
     *
     * @return Agenda
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
     * Set categoriaAgenda
     *
     * @param \AppBundle\Entity\CategoriaAgenda $categoriaAgenda
     *
     * @return Agenda
     */
    public function setCategoriaAgenda(\AppBundle\Entity\CategoriaAgenda $categoriaAgenda = null)
    {
        $this->categoriaAgenda = $categoriaAgenda;

        return $this;
    }

    /**
     * Get categoriaAgenda
     *
     * @return \AppBundle\Entity\CategoriaAgenda
     */
    public function getCategoriaAgenda()
    {
        return $this->categoriaAgenda;
    }

    /**
     * Add direccion
     *
     * @param \AppBundle\Entity\DireccionAgenda $direccion
     *
     * @return Agenda
     */
    public function addDireccion(\AppBundle\Entity\DireccionAgenda $direccion)
    {
        $direccion->setAgenda($this);
        $this->direccion->add($direccion);
        return $this;
    }

    /**
     * Remove direccion
     *
     * @param \AppBundle\Entity\DireccionAgenda $direccion
     */
    public function removeDireccion(\AppBundle\Entity\DireccionAgenda $direccion)
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
