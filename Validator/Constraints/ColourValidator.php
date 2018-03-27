<?php
namespace Hillrange\Form\Validator\Constraints;

use Hillrange\Form\Util\ColourManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ColourValidator extends ConstraintValidator
{
    /**
     * @param mixed $colour
     * @param Constraint $constraint
     */
    public function validate($colour, Constraint $constraint)
    {
        if (empty($colour))
            return;

        $value = ColourManager::formatColour($colour);

        if (empty($value))
            $this->context->buildViolation($constraint->message)
                ->setParameter('%{colour}', $value)
                ->addViolation();
    }
}