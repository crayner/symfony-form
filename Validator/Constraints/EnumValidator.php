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
	    $this->context->buildViolation('here at '.__METHOD__.':'.__LINE__)
            ->addViolation();

		$method = $constraint->method;
		$class = new $constraint->class;
		$source = $class->$method();
        $t = in_array($value, $source);

        if (empty($value))
            return;

        if (! $t)
			$this->context->buildViolation($constraint->message)
				->setParameter('%{value}', $value)
				->setParameter('%{enum}', implode(', ', $source))
                ->setTranslationDomain($constraint->transDomain)
				->addViolation();
	}
}