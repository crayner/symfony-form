<?php
namespace Hillrange\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
	/**
	 * @var SettingManager
	 */
	private $format;

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'format' => 'D, jS M/Y',
			]
		);
	}

	public function getParent()
	{
		return \Symfony\Component\Form\Extension\Core\Type\DateType::class;
	}

	public function getBlockPrefix()
	{
		return 'hillrange_date';
	}
}