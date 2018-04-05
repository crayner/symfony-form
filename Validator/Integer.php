<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\IntegerValidator;
use Symfony\Component\Validator\Constraint;

class Integer extends Constraint
{
    /**
     * @var string
     */
	public $message = 'integer.invalid.message';

    /**
     * @var string
     */
    public $transDomain = 'validators';

    /**
     * @return string
     */
    public function validatedBy()
	{
		return IntegerValidator::class;
	}
}
