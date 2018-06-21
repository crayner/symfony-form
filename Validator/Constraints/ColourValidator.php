<?php
namespace Hillrange\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ColourValidator
 * @package Hillrange\Form\Validator\Constraints
 */
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

        if (! in_array($constraint->enforceType, ['any', 'hex', 'rgb', 'rgba', 'hsl', 'hsla'])) {
            $this->context->buildViolation('colour.validation.enforce_type')
                ->setParameter('%{format}', $constraint->enforceType)
                ->setTranslationDomain('FormTheme')
                ->addViolation();
            return ;
        }

        $colour = str_replace(' ', '', $colour);

        $regex = "/^(#?([a-f\d]{3}|[a-f\d]{6}))$/";
        if (preg_match($regex, $colour) && in_array($constraint->enforceType, ['any', 'hex']))
            return $colour;

        $regex = "/^rgb\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\)$/";

        if (preg_match($regex, $colour) && in_array($constraint->enforceType, ['any', 'rgb']))
            return $colour;

        $regex = "/^rgba\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0?\.([\d]{1,2})|1(\.0)?)\)$/";

        if (preg_match($regex, $colour) && in_array($constraint->enforceType, ['any', 'rgba']))
            return $colour;

        $regex = "/^hsl\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),(0|100|\d{1,2})%,(0|100|\d{1,2})%\)$/";

        if (preg_match($regex, $colour) && in_array($constraint->enforceType, ['any', 'hsl']))
            return $colour;

        $regex = "/^hsla\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),(0|100|\d{1,2})%,(0|100|\d{1,2})%,(0?\.\d|1(\.0)?)\)$/";

        if (preg_match($regex, $colour) && in_array($constraint->enforceType, ['any', 'hsla']))
            return $colour;

        $this->context->buildViolation($constraint->message)
            ->setParameter('%{colour}', $colour)
            ->setParameter('%{format}', $constraint->enforceType )
            ->setTranslationDomain($constraint->transDomain === 'validators' ? 'FormTheme' : $constraint->transDomain )
            ->addViolation();

        return $colour;
/*

        $regex = "/^(#?([a-f\d]{3}|[a-f\d]{6})|
        rgb\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\)|
        rgba\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),(0?\.([\d]{1,2})|1(\.0)?)\)|
        hsl\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),(0|100|\d{1,2})%,(0|100|\d{1,2})%\)|hsla\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),(0|100|\d{1,2})%,(0|100|\d{1,2})%,(0?\.\d|1(\.0)?)\))$/";
*/
    }
}