<?php

namespace AppBundle\Form;


use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NoticiaType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'titulo' )
			->add( 'resumen' )
			->add( 'cuerpo', CKEditorType::class )
			->add( 'orden' )
			->add( 'visibleDesde',
				DateTimeType::class,
				array(
					'widget' => 'single_text',
					'format' => 'dd/MM/yyyy',
					'attr'   => array(
						'class'           => 'datepicker',
						'data-dateformat' => 'dd/mm/yy',
						'placeholder'     => 'Selecciona una fecha'
					)
				)

			)
			->add( 'visibleHasta',
				DateTimeType::class,
				array(
					'widget' => 'single_text',
					'format' => 'dd/MM/yyyy',
					'attr'   => array(
						'class'           => 'datepicker',
						'data-dateformat' => 'dd/mm/yy',
						'placeholder'     => 'Selecciona una fecha'
					)
				)

			)
			->add( 'activo' )
			->add( 'categoriaNoticia',
				'entity',
				array(
					'class' => 'AppBundle\Entity\CategoriaNoticia',
//					'multiple' => true,
					'attr'  => array( 'multiple' => true, 'data-widget' => 'select2' ),


				) )
			->add( 'fotoNoticias',
				'collection',
				array(
					'type'         => new FotoNoticiaType(),
					'allow_add'    => true,
					'allow_delete' => true,
				) );
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( array(
			'data_class' => 'AppBundle\Entity\Noticia'
		) );
	}
}
