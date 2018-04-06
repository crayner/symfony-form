<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\UpperCaseOnlyValidator;
use Symfony\Component\Validator\Constraint;

class UpperCaseOnly extends Constraint
{
	/**
	 * @var string
	 */
	public $message = 'upper_case_only.validator.error';

	/**
	 * @var bool
	 */
	public $repair = true;

    /**
     * @var string
     */
	public $transDomain = 'validators';

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return UpperCaseOnlyValidator::class;
	}
}
