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
     * @var string
     */
    private $buttonClassOn;

    /**
     * @return null|string
     */
    public function getParent()
	{
		return HiddenType::class;
	}

    /**
     * @return null|string
     */
    public function getBlockPrefix()
	{
		return 'hillrange_toggle';
	}

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars = array_replace($view->vars,
			array(
				'div_class' => $options['div_class'],
			)
		);
        $view->vars['button_class_off'] = $options['button_class_off'] ?: $this->buttonClassOff;
        $view->vars['button_class_off'] .= ' ' . $options['button_merge_class'];
        $view->vars['button_class_off'] = trim($view->vars['button_class_off']);
        $view->vars['button_class_on'] = $options['button_class_on'] ?: $this->buttonClassOn;
        $view->vars['button_class_on'] .= ' ' . $options['button_merge_class'];
        $view->vars['button_class_on'] = trim($view->vars['button_class_on']);
        $view->vars['use_font_awesome'] = $options['use_font_awesome'] ?: $this->isUseFontAwesome();
        $view->vars['fa-type-swap'] = '';

        if ($view->vars['use_font_awesome'])
        {
            $icon = '';
            $type = '';
            if (preg_match('#^far | far | far$#', $view->vars['button_class_off'], $matches) > 0) {
                $icon .= 'far ';
                $type = 'far';
                $view->vars['button_class_off'] = str_replace($matches, '', $view->vars['button_class_off']);
            }

            if (preg_match('#^fas | fas | fas$#', $view->vars['button_class_off'], $matches) > 0) {
                $icon .= 'fas ';
                $type = 'fas';
                $view->vars['button_class_off'] = str_replace($matches, '', $view->vars['button_class_off']);
            }

            if (preg_match('#^fal | fal | fal$#', $view->vars['button_class_off'], $matches) > 0) {
                $icon .= 'fal ';
                $type = 'fal';
                $view->vars['button_class_off'] = str_replace($matches, '', $view->vars['button_class_off']);
            }

            if (preg_match('#^fab | fab | fab$#', $view->vars['button_class_off'], $matches) > 0) {
                $icon .= 'fab ';
                $type = 'fab';
                $view->vars['button_class_off'] = str_replace($matches, '', $view->vars['button_class_off']);
            }

            if (preg_match('#fa-([\S][\w-]*)#', $view->vars['button_class_off'], $matches) > 0) {
                $icon .= $matches[0] . ' ';
                $view->vars['button_class_off'] = str_replace($matches, '', $view->vars['button_class_off']);
            }

            $view->vars['button_class_off'] = trim($view->vars['button_class_off']);
            $view->vars['icon_class_off'] = trim($icon);
            $view->vars['fa_type_off'] = trim($type);

            $icon = '';
            $type = '';
            if (preg_match('#^far | far | far$#', $view->vars['button_class_on'], $matches) > 0) {
                $icon .= 'far ';
                $type = 'far';
                $view->vars['button_class_on'] = str_replace($matches, '', $view->vars['button_class_on']);
            }

            if (preg_match('#^fas | fas | fas$#', $view->vars['button_class_on'], $matches) > 0) {
                $icon .= 'fas ';
                $type = 'fas';
                $view->vars['button_class_on'] = str_replace($matches, '', $view->vars['button_class_on']);
            }

            if (preg_match('#^fal | fal | fal$#', $view->vars['button_class_on'], $matches) > 0) {
                $icon .= 'fal ';
                $type = 'fal';
                $view->vars['button_class_on'] = str_replace($matches, '', $view->vars['button_class_on']);
            }

            if (preg_match('#^fab | fab | fab$#', $view->vars['button_class_on'], $matches) > 0) {
                $icon .= 'fab ';
                $type = 'fab';
                $view->vars['button_class_on'] = str_replace($matches, '', $view->vars['button_class_on']);
            }

            if (preg_match('#fa-([\S][\w-]*)#', $view->vars['button_class_on'], $matches) > 0) {
                $icon .= $matches[0] . ' ';
                $view->vars['button_class_on'] = str_replace($matches, '', $view->vars['button_class_on']);
            }

            $view->vars['button_class_on'] = trim($view->vars['button_class_on']);
            $view->vars['icon_class_on'] = trim($icon);
            $view->vars['fa_type_on'] = trim($type);

        }
	}

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'compound'   => false,
				'required'   => false,
				'div_class'  => 'toggleRight',
				'button_class_off' => null,
				'button_class_on' => null,
                'button_merge_class'    => '',
                'use_font_awesome' => false,
			)
		);
	}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new ToggleTransformer());
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
    public function setButtonClassOn(string $buttonClassOn = 'toggle toggle-thumbs-up')
    {
        $this->buttonClassOn = $buttonClassOn;

        return $this;
    }

    /**
     * @var bool 
     */
    private $useFontAwesome = false;

    /**
     * @return bool
     */
    public function isUseFontAwesome(): bool
    {
        return $this->useFontAwesome ? true : false ;
    }

    /**
     * @param bool|null $useFontAwesome
     * @return ToggleType
     */
    public function setUseFontAwesome(?bool $useFontAwesome): ToggleType
    {
        $this->useFontAwesome = $useFontAwesome ? true : false;
        return $this;
    }
}