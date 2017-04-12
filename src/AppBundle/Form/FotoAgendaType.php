<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FotoAgendaType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'foto' )
			->add( 'descripcion' )
            ->add('tipo', ChoiceType::class, array(
                'choices'  => array(
                    'Imagen' => 'imagen',
                    'Audio' => 'audio',
                    'Video' => 'video',
                ),
                // *this line is important*
                'choices_as_values' => true,
            ))
		;
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( array(
			'data_class' => 'AppBundle\Entity\FotoAgenda'
		) );
	}
}
