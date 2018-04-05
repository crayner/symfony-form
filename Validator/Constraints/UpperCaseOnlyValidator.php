<?php
namespace Hillrange\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UpperCaseOnlyValidator extends ConstraintValidator
{
	/**
	 * @param mixed      $value
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if ($constraint->repair)
			$value = strtoupper($value);

		if (preg_match('/[a-z]/', $value))
			$this->context->buildViolation($constraint->message)
				->setParameter('%value%', $value)
                ->setTranslationDomain($constraint->transDomain)
				->addViolation();

		return $value;
	}
}