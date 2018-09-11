<?php
namespace Hillrange\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EnumValidator extends ConstraintValidator
{
	/**
	 * @param mixed      $value
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		$method = $constraint->method;
		$class = new $constraint->class;
		$source = $class->$method();
        $t = true;
        $value = is_array($value) ? $value : [$value];
        foreach($value as $q=>$w)
            if (! in_array($w, $source))
            {
                $t = false;
                break;
            } else {
                unset($value[$q]);
            }

        if (empty($value) && $t)
            return;

        $this->context->buildViolation($constraint->message)
            ->setParameter('%{value}', $value)
            ->setParameter('%{enum}', implode(', ', $source))
            ->setTranslationDomain($constraint->transDomain)
            ->addViolation();
	}
}