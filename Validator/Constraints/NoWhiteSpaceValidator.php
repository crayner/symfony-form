<?php
namespace Hillrange\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NoWhiteSpaceValidator extends ConstraintValidator
{
	/**
	 * @param mixed      $value
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if ($constraint->repair)
			$value = preg_replace('/\s/', '', $value);

		if (preg_match('/\s/', $value))
			$this->context->buildViolation($constraint->message)
				->setParameter('%value%', $value)
                ->setTranslationDomain($constraint->transDomain)
				->addViolation();

		return $value;
	}
}