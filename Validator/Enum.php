<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\IntegerValidator;
use Symfony\Component\Validator\Constraint;

class Enum extends Constraint
{
    /**
     * @var string
     */
	public $message = 'enum.invalid.message';

    /**
     * @var string
     */
    public $transDomain = 'validators';

    /**
     * @return string
     */
    public $class;

    /**
     * @return string
     */
    public $method;

    /**
     * @return string
     */
    public function validatedBy()
	{
		return IntegerValidator::class;
	}

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return [
            'class',
            'method'
        ];
    }
}
