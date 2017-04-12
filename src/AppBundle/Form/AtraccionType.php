<?php

namespace AppBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtraccionType extends AbstractType {
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
			->add( 'activo' )
			->add( 'categoriaAtraccion',
				EntityType::class,
				array(
					'class' => 'AppBundle\Entity\CategoriaAtraccion',
//					'multiple' => true,
					'attr'  => array( 'multiple' => true, 'data-widget' => 'select2' ),


				) )
			->add( 'fotoAtraccion',
				CollectionType::class,
				array(
					'type'         => new FotoAtraccionType(),
					'allow_add'    => true,
					'allow_delete' => true,
				) )
            ->add( 'direccion',
                CollectionType::class,
                array(
                    'type'         => new DireccionAtraccionType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                ) );
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( array(
			'data_class' => 'AppBundle\Entity\Atraccion'
		) );
	}
}
