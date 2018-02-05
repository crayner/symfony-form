<?php
namespace Hillrange\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class TimeType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'compound' => true,
				'multiple' => false,
			)
		);
	}

	public function getBlockPrefix()
	{
		return 'hillrange_time';
	}

	public function getParent()
	{
		return \Symfony\Component\Form\Extension\Core\Type\TimeType::class;
	}
}