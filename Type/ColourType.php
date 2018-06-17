<?php
namespace Hillrange\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

/**
 * Class ColourType
 * @package Hillrange\Form\Type
 */
class ColourType extends AbstractType
{
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