<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\NoWhiteSpaceValidator;
use Symfony\Component\Validator\Constraint;

class NoWhiteSpace extends Constraint
{
	/**
	 * @var string
	 */
	public $message = 'no_white_space.validator.error';

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
		return NoWhiteSpaceValidator::class;
	}
}
