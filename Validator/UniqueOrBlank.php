<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\UniqueOrBlankValidator;
use Symfony\Component\Validator\Constraint;

class UniqueOrBlank extends Constraint
{
    /**
     * @var string
     */
	public $message = 'unique.blank.invalid';

    /**
     * @var
     */
	public $data_class;

    /**
     * @var string
     */
	public $field;

    /**
     * @var string
     */
    public $transDomain = 'validators';

    /**
     * @return string
     */
    public function validatedBy()
	{
		return UniqueOrBlankValidator::class;
	}

    /**
     * @return array
     */
    public function getRequiredOptions()
	{
		return [
			'field',
			'data_class',
		];
	}

}
