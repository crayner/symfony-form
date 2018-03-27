<?php
namespace Hillrange\Form\Util;

class ColourManager
{
    /**
     * @param null|string $colour
     * @return null|string
     */
    static public function formatColour(?string $colour): ?string
    {
        if (empty($colour))
            return null;

        $colour = str_replace('#', '', $colour);

        $x = explode(',', $colour);
        if (count($x) === 3)
        {
            $colour = '';
            foreach($x as $num)
                $colour .= str_pad(dechex($num),2, '0', STR_PAD_LEFT);
        } else
            $colour = $x[0];

        $x = preg_match('/^(?:[0-9a-fA-F]{3}){2,2}$/', $colour);
        if ($x !== false && $x > 0)
            return $colour;

        return null;
    }
}