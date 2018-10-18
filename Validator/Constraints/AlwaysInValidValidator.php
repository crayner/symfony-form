<?php
namespace Hillrange\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AlwaysInValidValidator
 * @package Hillrange\Form\Validator\Constraints
 */
class AlwaysInValidValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('%{name}', $this->context->getObject()->getConfig()->getName())
            ->addViolation();
    }
}