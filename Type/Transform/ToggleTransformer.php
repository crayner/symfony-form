<?php
namespace Hillrange\Form\Type\Transform;

use Symfony\Component\Form\DataTransformerInterface;

class ToggleTransformer implements DataTransformerInterface
{
	/**
	 * Transforms an string to boolean
	 *
	 * @param   $data
	 *
	 * @return string
	 */
	public function transform($data)
	{
		return $data == '1' ? true : false ;
	}

	/**
	 * Transforms a string to boolean
	 *
	 * @param mixed $data
	 *
	 * @return null|string
	 * @internal param $ null|File
	 */
	public function reverseTransform($data)
	{
		return $data ? "1" : "0" ;
	}
}