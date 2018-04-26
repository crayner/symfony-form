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
		if (empty($value))
			return;

		$method = $constraint->method;
		$class = new $constraint->class;
		$source = $class->$method();
		if (! in_array($value, $source))
			$this->context->buildViolation($constraint->message)
				->setParameter('%{value}', $value)
				->setParameter('%{enum}', implode(', ', $source))
                ->setTranslationDomain($constraint->transDomain)
				->addViolation();
	}
}