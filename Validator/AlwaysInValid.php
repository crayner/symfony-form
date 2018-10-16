<?php
namespace Hillrange\Form\Validator;

use Hillrange\Form\Validator\Constraints\AlwaysInValidValidator;
use Symfony\Component\Validator\Constraint;

class AlwaysInValid extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This field %{name} is always invalid';

    /**
     * @var string
     */
    public function validatedBy()
    {
        return AlwaysInValidValidator::class;
    }
}