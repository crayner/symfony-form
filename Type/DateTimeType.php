<?php
namespace Hillrange\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'format' => 'yyyyMMMdd',
                'wrapTime' => false,
			]
		);
	}

    /**
     * {@inheritdoc}
     */
	public function getParent()
	{
		return \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class;
	}

    /**
     * {@inheritdoc}
     */
	public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['wrapTime'] = $options['wrapTime'];
        $view->vars['format'] = $options['format'];
    }
}