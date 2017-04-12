<?php

namespace AppBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgendaType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'titulo' )
			->add( 'resumen' )
			->add( 'cuerpo', CKEditorType::class )
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
			->add( 'fechaEventoDesde',
				DateTimeType::class,
				array(
					'widget' => 'single_text',
					'format' => 'dd/MM/yyyy HH:mm',
					'attr'   => array(
						'class'           => 'datetimepicker',
//						'data-dateformat' => 'dd/mm/yy',
						'placeholder'     => 'Selecciona una fecha'
					)
				)

			)
			->add( 'fechaEventoHasta',
				DateTimeType::class,
				array(
					'widget' => 'single_text',
					'format' => 'dd/MM/yyyy HH:mm',
					'attr'   => array(
						'class'           => 'datetimepicker',
//						'data-dateformat' => 'dd/mm/yy hh:i',
						'placeholder'     => 'Selecciona una fecha'
					)
				)

			)
			->add( 'orden' )
			->add( 'activo' )
			->add( 'categoriaAgenda',
				EntityType::class,
				array(
					'class' => 'AppBundle\Entity\CategoriaAgenda',
//					'multiple' => true,
					'attr'  => array( 'multiple' => true, 'data-widget' => 'select2' ),


				) )
			->add( 'fotoAgenda',
				CollectionType::class,
				array(
					'type'         => new FotoAgendaType(),
					'allow_add'    => true,
					'allow_delete' => true,
				) )
			->add( 'direccion',
				CollectionType::class,
				array(
					'type'         => new DireccionAgendaType(),
					'allow_add'    => true,
					'allow_delete' => true,
				) );
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( array(
			'data_class' => 'AppBundle\Entity\Agenda'
		) );
	}
}
