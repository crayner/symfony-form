<?php
namespace Hillrange\Form\Type;

use Hillrange\Form\Type\EventSubscriber\ColourSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ColourType
 * @package Hillrange\Form\Type
 */
class ColourType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new ColourSubscriber());
    }

    /**
     * getParent
     *
     * @return null|string
     */
    public function getParent()
	{
		return ColorType::class;
	}

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
	{
		return 'hillrange_colour';
	}
}