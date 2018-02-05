<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\UniqueOrBlankValidator;
use Symfony\Component\Validator\Constraint;

class UniqueOrBlank extends Constraint
{
	public $message = 'unique.blank.invalid';

	public $data_class;

	public $field;

	public function validatedBy()
	{
		return UniqueOrBlankValidator::class;
	}


	public function getRequiredOptions()
	{
		return [
			'field',
			'data_class',
		];
	}

}
