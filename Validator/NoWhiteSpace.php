<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\NoWhiteSpaceValidator;
use Symfony\Component\Validator\Constraint;

class NoWhiteSpace extends Constraint
{
	/**
	 * @var string
	 */
	public $message = 'nowhitespace.error';

	/**
	 * @var bool
	 */
	public $repair = true;

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return NoWhiteSpaceValidator::class;
	}
}
