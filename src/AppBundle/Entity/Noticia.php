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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Noticia
 *
 * @ORM\Table(name="noticias")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoticiaRepository")
 * @ExclusionPolicy("all")
 */
class Noticia
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
     * @var int
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FotoNoticia", mappedBy="noticia", cascade={"persist", "remove"})
     */
    private $fotoNoticias;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategoriaNoticia", inversedBy="noticia")
     * @ORM\JoinColumn(name="categoria_noticia_id", referencedColumnName="id", nullable=true)
     */
    private $categoriaNoticia;

    /**
     * @var DateTime $creado
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
     * @SerializedName("archivos")
     * @VirtualProperty
     */
    public function getArchivos()
    {
        $archivos = array();
        $archivos['fotos'] = array();
        $archivos['audios'] = array();
        $archivos['videos'] = array();
        foreach ($this->fotoNoticias as $item) {
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

    public function __toString()
    {
//		return $this->getTitulo();
        return "";
    }

    /**
     * @SerializedName("categoria")
     * @VirtualProperty
     */
    public function getCategoria()
    {
        return $this->getCategoriaNoticia() ? $this->getCategoriaNoticia()->getNombre() : null;
    }

    /**
     * @SerializedName("icono")
     * @VirtualProperty
     */
    public function getIcono()
    {
        return $this->getCategoriaNoticia() ? $this->getCategoriaNoticia()->getIcono() : null;
    }


    /**
     * @SerializedName("imagenes")
     * @VirtualProperty
     */
    public function getFotos()
    {

        $aRes = array();

        foreach ($this->getFotoNoticias() as $fotoNoticia) {
            $aRes[] = array(
                'path' => $fotoNoticia->getUploadDir() . $fotoNoticia->getRuta(),
                'descripcion' => $fotoNoticia->getDescripcion()
            );
        }

        return $aRes;
    }

    /**
     * @SerializedName("publicado_por")
     * @VirtualProperty
     */
    public function getPublicadoPor()
    {

        return $this->getCreadoPor()->getUsername();


    }

    /**
     * @SerializedName("imagen_principal")
     * @VirtualProperty
     */
    public function getImagenPrincipal()
    {

        if ($this->getFotoNoticias()->count() > 0) {

            return $this->getFotoNoticias()->first()->getUploadDir() . $this->getFotoNoticias()->first()->getRuta();
        }

        return null;


    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fotoNoticias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Noticia
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
     * @return Noticia
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
     * @return Noticia
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
     *
     * @return Noticia
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
     * @return Noticia
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
     * Add fotoNoticias
     *
     * @param \AppBundle\Entity\FotoNoticia $fotoNoticias
     *
     * @return Noticia
     */
    public function addFotoNoticion(\AppBundle\Entity\FotoNoticia $fotoNoticias)
    {
        $this->fotoNoticias[] = $fotoNoticias;
        $fotoNoticias->setNoticia($this);
        return $this;
    }

    /**
     * Remove fotoNoticias
     *
     * @param \AppBundle\Entity\FotoNoticia $fotoNoticias
     */
    public function removeFotoNoticion(\AppBundle\Entity\FotoNoticia $fotoNoticias)
    {
        $this->fotoNoticias->removeElement($fotoNoticias);
    }

    /**
     * Get fotoNoticias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotoNoticias()
    {
        return $this->fotoNoticias;
    }

    /**
     * Get fotoNoticias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotoNoticion()
    {
        return $this->fotoNoticias;
    }


    /**
     * Set categoriaNoticia
     *
     * @param \AppBundle\Entity\CategoriaNoticia $categoriaNoticia
     *
     * @return Noticia
     */
    public function setCategoriaNoticia(\AppBundle\Entity\CategoriaNoticia $categoriaNoticia = null)
    {
        $this->categoriaNoticia = $categoriaNoticia;

        return $this;
    }

    /**
     * Get categoriaNoticia
     *
     * @return \AppBundle\Entity\CategoriaNoticia
     */
    public function getCategoriaNoticia()
    {
        return $this->categoriaNoticia;
    }

    /**
     * Set creado
     *
     * @param \DateTime $creado
     *
     * @return Noticia
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
     * @return Noticia
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
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     *
     * @return Noticia
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
     * @return Noticia
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
     * Set visibleDesde
     *
     * @param \DateTime $visibleDesde
     *
     * @return Noticia
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
     * @param \DateTime $visibleHasta
     *
     * @return Noticia
     */
    public function setVisibleHasta($visibleHasta)
    {
        $this->visibleHasta = $visibleHasta;

        return $this;
    }

    /**
     * Get visibleHasta
     *
     * @return \DateTime
     */
    public function getVisibleHasta()
    {
        return $this->visibleHasta;
    }
}
