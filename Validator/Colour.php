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
     * @return string
     */
    public function validatedBy()
    {
        return ColourValidator::class;
    }
}