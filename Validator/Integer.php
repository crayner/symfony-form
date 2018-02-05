<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\IntegerValidator;
use Symfony\Component\Validator\Constraint;

class Integer extends Constraint
{
	public $message = 'integer.invalid.message';

	public function validatedBy()
	{
		return IntegerValidator::class;
	}
}
