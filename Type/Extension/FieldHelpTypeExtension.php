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
 * Adda the ability to add help params to all elements of a form.
 */
class FieldHelpTypeExtension extends AbstractTypeExtension
{
    /**
     * buildView
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
        $view->vars['help_params'] = $options['help_params'];
        $view->vars['element_class'] = $options['element_class'];
        $attr = empty($options['attr']) ? [] : $options['attr'];

        $view->vars['attr'] = $attr;
	}

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'help_params' => [],
                'element_class' => '',
			]
		);
	}

    /**
     * getExtendedType
     *
     * @return array
     */
	public static function getExtendedTypes()
	{
		return [FormType::class];
	}
}