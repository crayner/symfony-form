<?php
namespace Hillrange\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'format' => 'yyyyMMMdd',
			]
		);
	}

    /**
     * getParent
     *
     * @return null|string
     */
	public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\DateType::class;
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
	public function getBlockPrefix()
	{
		return 'hillrange_date';
	}
}