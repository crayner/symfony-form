<?php
namespace Hillrange\Form\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FieldHelpTypeExtension
 * @package Hillrange\Form
 *
 * Adda the ability to add help and auto_complete to all elements of a form.
 */
class FieldHelpTypeExtension extends AbstractTypeExtension
{
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['help'] = $options['help'];
        $view->vars['help_params'] = $options['help_params'];
        $view->vars['element_class'] = $options['element_class'];
        $attr = empty($options['attr']) ? [] : $options['attr'];

        if ($options['auto_complete'] === false || empty($options['auto_complete']))
            $options['auto_complete'] = 'off';
        if (! empty($options['auto_complete']) && is_string($options['auto_complete']))
            $attr = array_merge($attr, ['autocomplete' => $options['auto_complete']]);

        $view->vars['attr'] = $attr;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'help'  => null,
				'help_params' => [],
                'element_class' => '',
                'auto_complete' => 'off',
			]
		);
	}

	public function getExtendedType()
	{
		return FormType::class;
	}
}