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

        $value = $colour;
        $colour = str_replace('#', '', $colour);

        $x = explode(',', $colour);
        if (count($x) === 3)
        {
            $colour = '';
            foreach($x as $num)
                $colour .= str_pad(dechex($num),2, '0', STR_PAD_LEFT);
        } else
            $colour = $x[0];

        $x = preg_match('^(?:[0-9a-fA-F]{3}){1,2}$', $colour);

        if ($x !== false && $x > 0)
            $this->context->buildViolation($constraint->message)
                ->setParameter('%{colour}', $value)
                ->addViolation();

    }
}