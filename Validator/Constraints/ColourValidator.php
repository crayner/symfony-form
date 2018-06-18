<?php
namespace Hillrange\Form\Validator\Constraints;

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

        $regex = "/^(#?([a-f\d]{3}|[a-f\d]{6})|rgb\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\)|rgba\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0?\.([\d]{1,2})|1(\.0)?)\)|hsl\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),(0|100|\d{1,2})%,(0|100|\d{1,2})%\)|hsla\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),(0|100|\d{1,2})%,(0|100|\d{1,2})%,(0?\.\d|1(\.0)?)\))$/";


        if (! preg_match($regex, $colour))
            $this->context->buildViolation($constraint->message)
                ->setParameter('%{colour}', $colour)
                ->setTranslationDomain($constraint->transDomain)
                ->addViolation();
    }
}