<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\Transform\ToggleTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleType extends AbstractType
{
    /**
     * @var string
     */
    private $buttonClassOff;

    /**
     * @var array
     */
    private $buttonToggleSwap;

	public function getParent()
	{
		return HiddenType::class;
	}

	public function getBlockPrefix()
	{
		return 'hillrange_toggle';
	}

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars = array_replace($view->vars,
			array(
				'div_class' => $options['div_class'],
			)
		);
        $view->vars['button_class_off'] = $options['button_class_off'] ?: $this->buttonClassOff;
        $view->vars['button_toggle_swap'] = $options['button_toggle_swap'] ?: $this->buttonToggleSwap;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'compound'   => false,
				'required'   => false,
				'div_class'  => 'toggleRight',
				'button_class_off' => null,
				'button_toggle_swap' => null,
			)
		);
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new ToggleTransformer());
	}

    /**
     * @param string $buttonClassOff
     * @return $this
     */
    public function setButtonClassOff(string $buttonClassOff = 'btn btn-danger toggle toggle-thumbs-down')
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
                'btn-danger',
                'btn-success',
                'toggle-thumbs-down',
                'toggle-thumbs-up',
            ];
        $this->buttonToggleSwap = $buttonToggleSwap;

        return $this;
    }
}