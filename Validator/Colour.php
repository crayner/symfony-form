<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\ColourValidator;
use Symfony\Component\Validator\Constraint;

class Colour extends Constraint
{
    /**
     * @var string
     */
    public $message = 'colour.validation.error';

    /**
     * @var string
     */
    public $transDomain = 'FormTheme';

    /**
     * @var string
     */
    public $enforceType = 'any';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return ColourValidator::class;
    }
}