<?php
namespace Hillrange\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleTypeExtension extends AbstractTypeExtension
{
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars = array_replace($view->vars,
			array(
				'div_class' => $options['div_class'],
			)
		);
		$view->vars['use_toggle'] = $options['use_toggle'];
        $view->vars['button_class_off'] = $options['button_class_off'];
        $view->vars['button_class_swap'] = $options['button_class_swap'];
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'value'      => '1',
				'compound'   => false,
				'required'   => false,
				'div_class'  => 'toggleRight',
				'use_toggle' => false,
				'button_class_off' => 'toggle toggle-thumbs-down',
				'button_toggle_swap' => [
					'toggle-thumbs-down',
                    'toggle-thumbs-up'
				],
			)
		);
	}

	/**
	 * @return string
	 */
	public function getExtendedType()
	{
		return CheckboxType::class;
	}
}