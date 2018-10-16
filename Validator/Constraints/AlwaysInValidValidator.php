<?php
namespace Hillrange\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AlwaysInValidValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('%{name}', $this->context->getObject()->getConfig()->getName())
            ->addViolation();
    }
}