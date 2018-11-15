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
        $object = $this->context->getObject();
        $name = null;
        if (is_object($object)) {
            if (method_exists($object, 'getConfig'))
                $name = $object->getConfig()->getName();
            if (! $name && method_exists($object, '__toString'))
                $name = $object->__toString();
            if (! $name && method_exists($object, 'getName'))
                $name = $object->getName();
            if (! $name)
                $name = get_class($object);
        } else
            $name = strval($object);
        $this->context->buildViolation($constraint->message)
            ->setParameter('%{name}', $name)
            ->addViolation();
    }
}