<?php
namespace Hillrange\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleTypeExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    private $buttonClassOff;

    /**
     * @var array
     */
    private $buttonToggleSwap;

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars = array_replace($view->vars,
			array(
				'div_class' => $options['div_class'],
			)
		);
		$view->vars['use_toggle'] = $options['use_toggle'];
        $view->vars['button_class_off'] = $options['button_class_off'] ?: $this->buttonClassOff;
        $view->vars['button_toggle_swap'] = $options['button_toggle_swap'] ?: $this->buttonToggleSwap;
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

    /**
     * @param string $buttonClassOff
     * @return $this
     */
    public function setButtonClassOff(string $buttonClassOff = 'toggle toggle-thumbs-down')
    {
        $this->buttonClassOff = $buttonClassOff;

        return $this;
    }

    /**
     * @param array $buttonToggleSwap
     * @return $this
     */
    public function setButtonToggleSwap(array $buttonToggleSwap = [])
    {
        if (empty($buttonToggleSwap))
            $buttonToggleSwap = [
                'toggle-thumbs-down',
                'toggle-thumbs-up',
            ];
        $this->buttonToggleSwap = $buttonToggleSwap;

        return $this;
    }
}